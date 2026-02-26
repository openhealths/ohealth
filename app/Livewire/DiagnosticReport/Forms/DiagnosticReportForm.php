<?php

declare(strict_types=1);

namespace App\Livewire\DiagnosticReport\Forms;

use App\Rules\InDictionary;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class DiagnosticReportForm extends Form
{
    public array $diagnosticReport;

    public array $observations;

    public string $knedp;

    public TemporaryUploadedFile $keyContainerUpload;

    public string $password;

    protected function rules(): array
    {
        return [
            'diagnosticReport.referralType' => ['nullable', 'string'],
            'diagnosticReport.primarySource' => ['required', 'boolean:strict'],
            'diagnosticReport.category.*.coding.*.system' => ['required', 'string'],
            'diagnosticReport.category.*.coding.*.code' => [
                'required',
                'string',
                new InDictionary('eHealth/diagnostic_report_categories')
            ],
            'diagnosticReport.code.identifier.value' => ['required', 'uuid'],
            'diagnosticReport.code.identifier.type.coding.*.system' => ['required', 'string'],
            'diagnosticReport.code.identifier.type.coding.*.code' => [
                'required',
                'string',
                new InDictionary('eHealth/resources')
            ],
            'diagnosticReport.paperReferral.requisition' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.paperReferral.requesterEmployeeName' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.paperReferral.requesterLegalEntityEdrpou' => [
                Rule::requiredIf(data_get($this->diagnosticReport, 'referralType') === 'paper'),
                'regex:/^[0-9]{8,10}$/',
                'string',
                'max:255'
            ],
            'diagnosticReport.paperReferral.requesterLegalEntityName' => [
                Rule::requiredIf(data_get($this->diagnosticReport, 'referralType') === 'paper'),
                'string',
                'max:255'
            ],
            'diagnosticReport.paperReferral.serviceRequestDate' => [
                Rule::requiredIf(data_get($this->diagnosticReport, 'referralType') === 'paper'),
                'date'
            ],
            'diagnosticReport.paperReferral.note' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.effectivePeriodStartDate' => ['nullable', 'date', 'before_or_equal:now',],
            'diagnosticReport.effectivePeriodStartTime' => ['nullable', 'date_format:H:i', 'before_or_equal:now'],
            'diagnosticReport.effectivePeriodEndDate' => [
                'nullable',
                'date',
                'before_or_equal:today',
                'after_or_equal:diagnosticReport.effectivePeriodStartDate'
            ],
            'diagnosticReport.effectivePeriodEndTime' => [
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $now = now()->format('H:i');
                    if ($value > $now) {
                        $fail('Час завершення прийому не може бути в майбутньому.');
                    }
                },
                'after:diagnosticReport.effectivePeriodStartTime'
            ],
            'diagnosticReport.issuedDate' => ['required', 'date', 'before_or_equal:now'],
            'diagnosticReport.issuedTime' => ['required', 'date_format:H:i', 'before_or_equal:now'],
            'diagnosticReport.conclusionCode.coding.*.system' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.conclusionCode.coding.*.code' => [
                'nullable',
                'string',
                new InDictionary('eHealth/ICD10_AM/condition_codes')
            ],
            'diagnosticReport.conclusion' => [
                // Must be filled when service category is diagnostic_procedure or imaging
                Rule::requiredIf(function () {
                    $codes = data_get($this->diagnosticReport, 'category.*.coding.*.code');

                    return collect($codes)->flatten()->filter()->contains(
                        fn ($code) => in_array($code, ['diagnostic_procedure', 'imaging'])
                    );
                }),
                'string',
                'max:1000'
            ],
            'diagnosticReport.division.identifier.value' => ['nullable', 'uuid'],
            'diagnosticReport.division.identifier.type.coding.*.system' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.division.identifier.type.coding.*.code' => [
                'nullable',
                'string',
                new InDictionary('eHealth/resources')
            ],
            'diagnosticReport.division.identifier.type.text' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.resultsInterpreter.reference.identifier.value' => ['nullable', 'uuid'],
            'diagnosticReport.resultsInterpreter.reference.identifier.type.coding.*.system' => [
                'nullable',
                'string',
                'max:255'
            ],
            'diagnosticReport.resultsInterpreter.reference.identifier.type.coding.*.code' => [
                'nullable',
                'string',
                new InDictionary('eHealth/resources')
            ],
            'diagnosticReport.resultsInterpreter.text' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.recordedBy.identifier.type.text' => ['nullable', 'string', 'max:255'],
            'diagnosticReport.recordedBy.identifier.type.coding.*.system' => ['required', 'string', 'max:255'],
            'diagnosticReport.recordedBy.identifier.type.coding.*.code' => [
                'required',
                'string',
                new InDictionary('eHealth/resources')
            ],
            'diagnosticReport.performer.reference.identifier.type.coding.*.system' => ['required', 'string', 'max:255'],
            'diagnosticReport.performer.reference.identifier.type.coding.*.code' => [
                'required',
                'string',
                new InDictionary('eHealth/resources')
            ],

            'observations.*.primarySource' => ['required', 'boolean:strict'],
            'observations.*.performer' => [
                'required_if:observations.*.primarySource,true',
                'array'
            ],
            'observations.*.performer.identifier.type.coding.*.system' => ['required', 'string', 'max:255'],
            'observations.*.performer.identifier.type.coding.*.code' => [
                'required',
                'string',
                new InDictionary('eHealth/resources')
            ],
            'observations.*.performer.identifier.type.text' => ['nullable', 'string', 'max:255'],
            'observations.*.reportOrigin' => [
                'required_if:observations.*.primarySource,false',
                'array'
            ],
            'observations.*.categories' => ['required', 'array'],
            'observations.*.categories.*.coding.*.system' => ['required', 'string', 'max:255'],
            'observations.*.categories.*.coding.*.code' => [
                'required',
                'string',
                new InDictionary(['eHealth/observation_categories', 'eHealth/ICF/observation_categories'])
            ],
            'observations.*.categories.*.text' => ['nullable', 'string', 'max:255'],
            'observations.*.code' => ['required', 'array'],
            'observations.*.code.coding.*.system' => ['required', 'string', 'max:255'],
            'observations.*.code.coding.*.code' => [
                'required',
                'string',
                new InDictionary(['eHealth/LOINC/observation_codes', 'eHealth/ICF/classifiers'])
            ],
            'observations.*.code.text' => ['nullable', 'string', 'max:255'],
            'observations.*.valueQuantity' => ['sometimes', 'array'],
            'observations.*.valueQuantity.value' => ['sometimes', 'numeric'],
            'observations.*.valueQuantity.comparator' => ['sometimes', 'string'],
            'observations.*.valueQuantity.unit' => ['sometimes', 'string'],
            'observations.*.valueQuantity.system' => ['sometimes', 'string'],
            'observations.*.valueQuantity.code' => ['sometimes', 'string'],
            'observations.*.valueCodeableConcept' => ['sometimes', 'array'],
            'observations.*.valueString' => ['sometimes', 'string'],
            'observations.*.valueBoolean' => ['sometimes', 'boolean'],
            'observations.*.valueDateTime' => ['sometimes', 'date'],
            'observations.*.components' => ['nullable', 'array'],
            'observations.*.method' => ['nullable', 'array'],
            'observations.*.method.coding.*.system' => ['nullable', 'string', 'max:255'],
            'observations.*.method.coding.*.code' => [
                'nullable',
                'string',
                new InDictionary('eHealth/observation_methods')
            ],
            'observations.*.method.text' => ['nullable', 'string', 'max:255'],
            'observations.*.bodySite' => ['nullable', 'array'],
            'observations.*.bodySite.coding.*.system' => ['nullable', 'string', 'max:255'],
            'observations.*.bodySite.coding.*.code' => [
                'nullable',
                'string',
                new InDictionary('eHealth/body_sites')
            ],
            'observations.*.bodySite.text' => ['nullable', 'string', 'max:255'],
            'observations.*.interpretation' => ['nullable', 'array'],
            'observations.*.interpretation.coding.*.system' => ['nullable', 'string', 'max:255'],
            'observations.*.interpretation.coding.*.code' => [
                'nullable',
                'string',
                new InDictionary('eHealth/observation_interpretations')
            ],
            'observations.*.interpretation.text' => ['nullable', 'string', 'max:255'],
            'observations.*.issuedDate' => ['required', 'date', 'before_or_equal:now'],
            'observations.*.issuedTime' => ['required', 'date_format:H:i', 'before_or_equal:now'],
            'observations.*.effectiveDate' => ['nullable', 'date', 'before_or_equal:now'],
            'observations.*.effectiveTime' => ['nullable', 'date_format:H:i'],
            'observations.*.comment' => ['nullable', 'string', 'max:1000']
        ];
    }

    /**
     * List of rules for signing Cipher form.
     *
     * @return array[]
     */
    public function rulesForSigning(): array
    {
        return [
            'knedp' => ['required', 'string'],
            'password' => ['required', 'string'],
            'keyContainerUpload' => ['required', 'file', 'extensions:dat,pfx,pk8,zs2,jks,p7s']
        ];
    }
}
