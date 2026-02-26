<?php

declare(strict_types=1);

namespace App\Livewire\Person\Records;

use App\Models\LegalEntity;
use App\Models\Person\Person;
use App\Traits\FormTrait;
use Livewire\Attributes\Locked;
use Livewire\Component;

abstract class BasePatientComponent extends Component
{
    use FormTrait;

    #[Locked]
    public string $patientId;

    /**
     * Patient full name.
     *
     * @var string
     */
    public string $patientFullName;

    public string $verificationStatus;

    /**
     * Patient UUID.
     *
     * @var string
     */
    protected string $uuid;

    public function boot(): void
    {
        if ($this->patientId) {
            $this->loadPatientData();
        }
    }

    public function mount(LegalEntity $legalEntity, string $patientId): void
    {
        $this->patientId = $patientId;
        $this->initializeComponent();
    }

    /**
     * Get all needed data from DB about patient.
     *
     * @return void
     */
    protected function loadPatientData(): void
    {
        $patient = Person::whereId($this->patientId)
            ->get(['uuid', 'first_name', 'last_name', 'second_name', 'verification_status'])
            ->firstOrFail();

        $this->patientFullName = $patient->fullName;
        $this->verificationStatus = $patient->verificationStatus;
        $this->uuid = $patient->uuid;
    }

    /**
     * A method that can be overridden in child classes for additional initialization.
     *
     * @return void
     */
    protected function initializeComponent(): void
    {
    }
}
