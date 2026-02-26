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

class LicenseCreate extends LicenseComponent
{
    public function mount(LegalEntity $legalEntity): void
    {
    }

    public function create(): void
    {
        if (Auth::user()->cannot('create', License::class)) {
            Session::flash('error', 'У вас немає дозволу на створення ліцензії');

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
            $response = EHealth::license()->create(data: $this->form->formatForApi($validated));

            try {
                $validated = $response->validate();
                License::create($response->map($validated));

                Session::flash('success', __('licenses.success.created'));
                $this->redirectRoute('license.index', [legalEntity()], navigate: true);
            } catch (Exception $exception) {
                $this->logDatabaseErrors($exception, 'Error while creating license');
                Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');

                return;
            }
        } catch (ConnectionException $exception) {
            $this->logConnectionError($exception, 'Error connecting when creating a license');
            Session::flash('error', "Виникла помилка. Відсутній зв'язок із ЕСОЗ");

            return;
        } catch (EHealthValidationException|EHealthResponseException $exception) {
            $this->logEHealthException($exception, 'Error when creating a license');

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
        return view('livewire.license.license-create');
    }
}
