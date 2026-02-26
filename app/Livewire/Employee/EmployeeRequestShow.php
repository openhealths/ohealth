<?php

declare(strict_types=1);

namespace App\Livewire\Employee;

use App\Models\Employee\EmployeeRequest;
use App\Models\LegalEntity;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;

class EmployeeRequestShow extends EmployeeComponent
{
    protected EmployeeRequest $employee;

    #[Locked]
    public ?int $employeeRequestId = null;
    public bool $isPersonalDataLocked = true;
    public bool $isPositionDataLocked = true;
    public bool $isPartyDataPartiallyLocked = false;
    public ?Collection $partyUsers = null;

    public function mount(LegalEntity $legalEntity, EmployeeRequest $employee_request): void
    {
        $this->loadDictionaries();
        $this->loadDivisions($legalEntity);
        $this->employee = $employee_request;
        $this->employeeRequestId = $employee_request->id;
        $this->form->hydrate($this->employee);
    }

    public function boot(): void
    {
        if ($this->employeeRequestId) {
            $this->employee = EmployeeRequest::findOrFail($this->employeeRequestId);
        }
    }

    public function render(): View
    {
        $partyExistingPositions = null;
        if ($this->employee->party) {
            $this->employee->party->loadMissing(['employees.division', 'employeeRequests.division']);
            $partyExistingPositions = $this->employee->party->employees->merge($this->employee->party->employeeRequests);
        }

        return view('livewire.employee.employee-show', [
            'employee' => $this->employee,
            'partyExistingPositions' => $partyExistingPositions
        ]);
    }
}
