<?php

declare(strict_types=1);

namespace App\Livewire\DiagnosticReport;

use App\Classes\eHealth\EHealth;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use App\Models\MedicalEvents\Sql\DiagnosticReport;
use App\Core\Arr;
use App\Repositories\MedicalEvents\Repository;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Throwable;

class DiagnosticReportCreate extends DiagnosticReportComponent
{
    /**
     * Validate and save data.
     *
     * @param  array  $diagnosticReportData
     * @return void
     */
    public function save(array $diagnosticReportData): void
    {
        if (Auth::user()?->cannot('create', DiagnosticReport::class)) {
            Session::flash('error', 'У вас немає дозволу на створення діагностичного звіту.');

            return;
        }

        $this->form->diagnosticReport = $diagnosticReportData;

        try {
            $validated = $this->form->validate();
        } catch (ValidationException $exception) {
            Session::flash('error', $exception->validator->errors()->first());
            $this->setErrorBag($exception->validator->getMessageBag());

            return;
        }

        $formattedData = $this->prepareFormattedData($validated);

        try {
            $this->storeValidatedData($formattedData);
        } catch (Exception|Throwable $exception) {
            Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');
            $this->logDatabaseErrors($exception, 'Error while saving diagnostic report');

            return;
        }

        Session::flash('success', 'Чернетку на створення діагностичного звіту успішно збережено.');
        $this->redirectRoute('persons.index', [legalEntity()], navigate: true);
    }

    /**
     * Submit encrypted data.
     *
     * @param  array  $diagnosticReportData
     * @return void
     */
    public function sign(array $diagnosticReportData): void
    {
        if (Auth::user()?->cannot('create', DiagnosticReport::class)) {
            Session::flash('error', 'У вас немає дозволу на створення діагностичного звіту.');

            return;
        }

        $this->form->diagnosticReport = $diagnosticReportData;

        try {
            $validated = $this->form->validate();
            $validatedCipher = $this->form->validate($this->form->rulesForSigning());
        } catch (ValidationException $exception) {
            Session::flash('error', $exception->validator->errors()->first());
            $this->setErrorBag($exception->validator->getMessageBag());

            return;
        }

        $formattedData = $this->prepareFormattedData($validated);

        try {
            $this->storeValidatedData($formattedData);
        } catch (Exception|Throwable $exception) {
            Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');
            $this->logDatabaseErrors($exception, 'Error while saving diagnostic report');

            return;
        }

        $signedContent = signatureService()->signData(
            Arr::toSnakeCase($formattedData),
            $validatedCipher['password'],
            $validatedCipher['knedp'],
            $validatedCipher['keyContainerUpload'],
            Auth::user()->party->taxId
        );

        try {
            EHealth::diagnosticReport()->create($this->patientUuid, ['signed_data' => $signedContent]);

            Session::flash('success', 'Заявку на створення діагностичного звіту успішно відправлено.');
            $this->redirectRoute('persons.index', [legalEntity()], navigate: true);
        } catch (ConnectionException $exception) {
            $this->logConnectionError($exception, 'Error connecting when creating a diagnostic report');
            Session::flash('error', "Виникла помилка. Відсутній зв'язок із ЕСОЗ.");

            return;
        } catch (EHealthValidationException|EHealthResponseException $exception) {
            $this->logEHealthException($exception, 'Error when creating a diagnostic report');

            if ($exception instanceof EHealthValidationException) {
                Session::flash('error', $exception->getFormattedMessage());
            } else {
                Session::flash('error', 'Помилка від ЕСОЗ: ' . $exception->getMessage());
            }

            return;
        }
    }

    /**
     * Prepare formatted data.
     *
     * @param  array  $validatedData
     * @return array
     */
    protected function prepareFormattedData(array $validatedData): array
    {
        $diagnosticReport = Repository::diagnosticReport()->formatEHealthRequest($validatedData['diagnosticReport']);

        $observations = [];
        if (isset($validatedData['observations'])) {
            $observations = Repository::observation()->formatEHealthRequest(
                $validatedData['observations'],
                $diagnosticReport['diagnosticReport']['id']
            );
        }

        return array_merge($diagnosticReport, $observations);
    }

    /**
     * Store validated formatted data into DB.
     *
     * @param  array  $formattedData
     * @return void
     * @throws Throwable
     */
    protected function storeValidatedData(array $formattedData): void
    {
        DB::transaction(static function () use ($formattedData) {
            $diagnosticReportId = Repository::diagnosticReport()->store([$formattedData['diagnosticReport']]);

            if (isset($formattedData['observations'])) {
                Repository::observation()->store($formattedData['observations'], diagnosticReportId: $diagnosticReportId);
            }
        });
    }
}
