<?php

declare(strict_types=1);

namespace App\Livewire\Declaration\Forms;

use App\Core\BaseForm;
use App\Enums\Declaration\Status;
use App\Enums\User\Role;
use App\Models\Declaration;
use App\Models\Employee\Employee;
use App\Models\Person\Person;
use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Validation\Rule;

class DeclarationForm extends BaseForm
{
    public string $personId;

    public string $employeeId = '';

    public string $divisionId = '';

    public ?string $authorizeWith = null;

    public ?string $parentDeclarationId = null;

    public ?int $verificationCode = null;

    public array $uploadedDocuments;

    /**
     * List of rules for creating.
     *
     * @return array
     */
    public function rulesForCreating(): array
    {
        return [
            'personId' => [
                'required',
                'uuid',
                Rule::exists('persons', 'uuid')
                    ->where(fn (QueryBuilder $query) => $query->whereNot('verification_status', 'NOT_VERIFIED'))
            ],
            'employeeId' => [
                'required',
                'uuid',
                Rule::exists('employees', 'uuid')
                    ->where(fn (QueryBuilder $query) => $query->where('employee_type', Role::DOCTOR)),
                // Match with age and doctor speciality
                $this->validateDoctorSpecialityForPatientAge()
            ],
            'divisionId' => ['required', 'uuid', Rule::exists('divisions', 'uuid')],
            'authorizeWith' => ['nullable', 'uuid'],
            'parentDeclarationId' => [
                'nullable',
                'uuid',
                Rule::exists('declarations', 'uuid')->where('status', Status::ACTIVE->value)
            ]
        ];
    }

    /**
     * List of rules for approving.
     *
     * @return array[]
     */
    public function rulesForApproving(): array
    {
        return ['verificationCode' => ['required', 'digits:4']];
    }

    /**
     * List of rules for uploading documents.
     *
     * @return array[]
     */
    public function rulesForUploadingDocuments(): array
    {
        return ['uploadedDocuments.*' => ['required', 'file', 'mimes:jpeg,jpg', 'max:10000']];
    }

    /**
     * Validate employee speciality vs patient age.
     *
     * @return Closure
     */
    protected function validateDoctorSpecialityForPatientAge(): Closure
    {
        return function (string $attribute, string $value, Closure $fail) {
            $speciality = Employee::whereUuid($this->employeeId)
                ->whereHas('specialities', fn (EloquentBuilder $query) => $query->where('speciality_officio', true))
                ->firstOrFail()
                ->specialities()
                ->where('speciality_officio', true)
                ->value('speciality');
            $patient = Person::whereUuid($this->personId)->firstOrFail();

            // https://e-health-ua.atlassian.net/wiki/spaces/EH/pages/18000740491/RC_+CSI-1323+_Create+declaration+request+v3#Check-that-doctor-speciality-meets-the-patient-age-requirements
            if ($speciality === 'THERAPIST' && $patient->age < Declaration::ADULT_AGE) {
                $fail('Терапевт може обслуговувати тільки пацієнтів віком від ' . Declaration::ADULT_AGE . ' років.');
            }

            if ($speciality === 'PEDIATRICIAN' && $patient->age >= Declaration::ADULT_AGE) {
                $fail('Педіатр може обслуговувати тільки пацієнтів віком до ' . Declaration::ADULT_AGE . ' років.');
            }
        };
    }
}
