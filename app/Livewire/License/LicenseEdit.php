<?php

declare(strict_types=1);

namespace App\Livewire\License;

use App\Classes\eHealth\EHealth;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use App\Models\LegalEntity;
use App\Models\License;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class for updating an additional license. Primary license can't be updated, see: https://e-health-ua.atlassian.net/wiki/spaces/ESOZ/pages/17533829974/BP-ESOZ-003-0003+MIS
 */
class LicenseEdit extends LicenseComponent
{
    public function mount(LegalEntity $legalEntity, License $license): void
    {
        $this->uuid = $license->uuid;
        $this->form->fill($license);
    }

    public function update(): void
    {
        if (Auth::user()->cannot('update', License::whereUuid($this->uuid)->first())) {
            Session::flash('error', 'У вас немає дозволу на оновлення ліцензії');

            return;
        }

        try {
            $validated = $this->form->validate();
        } catch (ValidationException $exception) {
            Session::flash('error', $exception->validator->errors()->first());
            $this->setErrorBag($exception->validator->getMessageBag());

            return;
        }

        try {
            $response = EHealth::license()->update($this->uuid, $this->form->formatForApi($validated));

            try {
                $validated = $response->validate();
                License::whereUuid($this->uuid)->update($response->map($validated));

                Session::flash('success', __('licenses.success.updated'));
                $this->redirectRoute('license.index', [legalEntity()], navigate: true);
            } catch (Exception $exception) {
                $this->logDatabaseErrors($exception, 'Error while updating license');
                Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');

                return;
            }
        } catch (ConnectionException $exception) {
            $this->logConnectionError($exception, 'Error connecting when updating a license');
            Session::flash('error', "Виникла помилка. Відсутній зв'язок із ЕСОЗ");

            return;
        } catch (EHealthValidationException|EHealthResponseException $exception) {
            $this->logEHealthException($exception, 'Error when updating a license');

            if ($exception instanceof EHealthValidationException) {
                Session::flash('error', $exception->getFormattedMessage());
            } else {
                Session::flash('error', 'Помилка від ЕСОЗ: ' . $exception->getMessage());
            }

            return;
        }
    }

    public function render(): View
    {
        return view('livewire.license.license-edit');
    }
}
