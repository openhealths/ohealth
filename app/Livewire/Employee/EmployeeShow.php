<?php

declare(strict_types=1);

namespace App\Livewire\Employee;

use App\Models\Employee\Employee;
use App\Models\LegalEntity;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class EmployeeShow extends EmployeeComponent
{
    protected Employee $employee;

    #[Locked]
    public ?int $employeeId = null;

    public bool $isPersonalDataLocked = true;
    public bool $isPositionDataLocked = true;
    public bool $isPartyDataPartiallyLocked = false;
    public ?Collection $partyUsers = null;

    public function mount(LegalEntity $legalEntity, Employee $employee): void
    {
        $this->loadDictionaries();
        $this->loadDivisions($legalEntity);
        $this->employee = $employee;
        $this->employeeId = $employee->id;
        $this->form->hydrate($this->employee);
    }

    public function boot(): void
    {
        if ($this->employeeId) {
            $this->employee = Employee::findOrFail($this->employeeId);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws Throwable
     * @throws NotFoundExceptionInterface
     */
    public function sync(): void
    {
        // Call the parent method using the currently loaded employee
        $success = $this->syncEmployeeData($this->employee);

        if ($success) {
            // Specific logic for the "Show" page:
            // We need to re-hydrate the form so the input fields show the new data immediately
            $this->employee->refresh();
            $this->form->hydrate($this->employee);
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
