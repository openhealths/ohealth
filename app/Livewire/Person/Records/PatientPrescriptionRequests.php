<?php

declare(strict_types=1);

namespace App\Livewire\Person\Records;

use Illuminate\Contracts\View\View;

class PatientPrescriptionRequests extends BasePatientComponent
{
    /** @var list<array<string, mixed>> */
    public array $prescriptionRequests = [];

    public string $filterStatus = '';
    public string $filterDoctor = '';
    public string $filterLegalEntity = '';

    public string $filterInteractionId = '';
    public string $filterCarePlanId = '';
    public string $filterAppointmentId = '';
    public string $filterEpisodeId = '';

    public bool $showAdditionalParams = false;

    protected function initializeComponent(): void
    {
        $this->loadPrescriptionRequests();
    }

    public function loadPrescriptionRequests(): void
    {
        // Fake data for the mockup
        $this->prescriptionRequests = [
            [
                'id' => 'fake-1',
                'requestNumber' => '0000-KQR5-ADR4-N5F1',
                'medicationName' => 'Дротаверин 20 мг/мл, р-н для ін\'єкцій',
                'statusLabel' => 'Нова',
                'statusBadge' => 'badge-green', // Actually in mockup it has a green text with white bg, wait it says 'badge-green' style might be different
                'medicationQty' => '10',
                'programName' => 'Доступні ліки',
                'periodLabel' => '20.08.2026-29.08.2026',
                'doctorName' => 'Петров І.І.',
                'legalEntityName' => 'Лікарня №4',
                'createdAt' => '20.08.2026',
                'dispensePeriodLabel' => '20.08.2026-29.08.2026',
                'appointmentId' => '1231-adsadas-aqeqe-casdda',
                'encounterId' => '1231-adsadas-aqeqe-casdda',
                'basisLabel' => 'Плані лікування',
                'basisId' => '1231-adsadas-aqeqe-casdda',
            ],
            [
                'id' => 'fake-2',
                'requestNumber' => '0000-KQR5-ADR4-N5F1',
                'medicationName' => 'Дротаверин 20 мг/мл, р-н для ін\'єкцій',
                'statusLabel' => 'Нова',
                'statusBadge' => 'badge-green',
                'medicationQty' => '10',
                'programName' => 'Доступні ліки',
                'periodLabel' => '20.08.2026-29.08.2026',
                'doctorName' => 'Петров І.І.',
                'legalEntityName' => 'Лікарня №4',
                'createdAt' => '20.08.2026',
                'dispensePeriodLabel' => '20.08.2026-29.08.2026',
                'appointmentId' => '1231-adsadas-aqeqe-casdda',
                'encounterId' => '1231-adsadas-aqeqe-casdda',
                'basisLabel' => 'Плані лікування',
                'basisId' => '1231-adsadas-aqeqe-casdda',
                'showDropdown' => true // to mimic mockup state for the second card
            ]
        ];
    }

    public function applyFilters(): void
    {
        $this->loadPrescriptionRequests();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'filterStatus',
            'filterDoctor',
            'filterLegalEntity',
            'filterInteractionId',
            'filterCarePlanId',
            'filterAppointmentId',
            'filterEpisodeId',
        ]);
        $this->loadPrescriptionRequests();
    }

    public function render(): View
    {
        return view('livewire.person.records.patient-prescription-requests');
    }
}
