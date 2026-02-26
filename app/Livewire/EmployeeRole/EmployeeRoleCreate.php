<?php

declare(strict_types=1);

namespace App\Livewire\EmployeeRole;

use App\Classes\eHealth\EHealth;
use App\Core\Arr;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use App\Livewire\EmployeeRole\Forms\EmployeeRoleForm as Form;
use App\Models\Employee\Employee;
use App\Models\EmployeeRole;
use App\Models\HealthcareService;
use App\Models\LegalEntity;
use App\Repositories\Repository;
use App\Traits\FormTrait;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Throwable;

class EmployeeRoleCreate extends Component
{
    use FormTrait;

    public Form $form;

    /**
     * List of active employees.
     *
     * @var array
     */
    public array $employees;

    /**
     * List of active healthcare services.
     *
     * @var array
     */
    public array $healthcareServices;

    public array $dictionaryNames = ['POSITION', 'SPECIALITY_TYPE'];

    public function mount(LegalEntity $legalEntity): void
    {
        $this->getDictionary();

        $this->employees = Employee::activeSpecialists($legalEntity->id)->get()
            ->map(static fn (Employee $employee) => [
                'uuid' => $employee->uuid,
                'fullName' => $employee->fullName,
                'position' => $employee->position
            ])
            ->toArray();

        $this->healthcareServices = HealthcareService::active()->get()->toArray();
    }

    public function create(): void
    {
        if (Auth::user()?->cannot('create', EmployeeRole::class)) {
            Session::flash('error', 'У вас немає дозволу на додавання ролі працівнику');

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
            $response = EHealth::employeeRole()->create(Arr::toSnakeCase($validated));
        } catch (ConnectionException $exception) {
            $this->logConnectionError($exception, 'Error connecting when creating an employee role');
            Session::flash('error', "Виникла помилка. Відсутній зв'язок із ЕСОЗ.");

            return;
        } catch (EHealthValidationException|EHealthResponseException $exception) {
            $this->logEHealthException($exception, 'Error when creating an employee role');

            if ($exception instanceof EHealthValidationException) {
                Session::flash('error', $exception->getFormattedMessage());
            } else {
                Session::flash('error', 'Помилка від ЕСОЗ: ' . $exception->getMessage());
            }

            return;
        }

        try {
            $validated = $response->validate();

            Repository::employeeRole()->store($response->map($validated));

            Session::flash('success', 'Роль успішно додано.');
            $this->redirectRoute('employee-role.index', [legalEntity()], navigate: true);
        } catch (Throwable $exception) {
            $this->logDatabaseErrors($exception, 'Failed to store employee role');
            Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');

            return;
        }
    }

    public function render(): View
    {
        return view('livewire.employee-role.employee-role-create');
    }
}
