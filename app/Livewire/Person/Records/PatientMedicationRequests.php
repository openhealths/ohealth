<?php

declare(strict_types=1);

namespace App\Livewire\Person\Records;

use App\Repositories\MedicalEvents\MedicationRequestRepository;
use Illuminate\Contracts\View\View;

class PatientMedicationRequests extends BasePatientComponent
{
    /** @var list<array<string, mixed>> */
    public array $medicationRequests = [];

    public string $filterStatus = '';

    public string $filterStartedAtFrom = '';

    public string $filterStartedAtTo = '';

    public string $filterEndedAtFrom = '';

    public string $filterEndedAtTo = '';

    public string $filterRequestNumber = '';

    public string $filterMedication = '';

    public string $filterInteractionId = '';

    public string $filterCarePlanId = '';

    public string $filterDoctor = '';

    public string $filterEpisodeId = '';

    public string $filterLegalEntity = '';

    public string $filterMedicalProgram = '';

    public string $filterCreatedAtFrom = '';

    public string $filterCreatedAtTo = '';

    public string $filterDispenseAvailableFrom = '';

    public string $filterDispenseAvailableTo = '';

    public bool $showAdditionalParams = false;

    protected function initializeComponent(): void
    {
        $this->loadMedicationRequests();
    }

    public function loadMedicationRequests(): void
    {
        if ($this->personId === null) {
            $this->medicationRequests = [];

            return;
        }

        $this->medicationRequests = app(MedicationRequestRepository::class)->searchByPersonId(
            $this->personId,
            [
                'status' => $this->filterStatus !== '' ? $this->filterStatus : null,
                'started_at_from' => $this->filterStartedAtFrom !== '' ? $this->filterStartedAtFrom : null,
                'started_at_to' => $this->filterStartedAtTo !== '' ? $this->filterStartedAtTo : null,
                'ended_at_from' => $this->filterEndedAtFrom !== '' ? $this->filterEndedAtFrom : null,
                'ended_at_to' => $this->filterEndedAtTo !== '' ? $this->filterEndedAtTo : null,
            ]
        );

        // TODO: Remove this fake data later
        if (empty($this->medicationRequests)) {
            $this->medicationRequests = [
                [
                    'id' => 'fake-1',
                    'uuid' => 'fake-uuid-1',
                    'requestNumber' => '0000-KQR5-A0R4-NSF1',
                    'medicationName' => 'Дротаверин 20 мг/мл, р-н для ін\'єкцій',
                    'statusBadge' => 'badge-green',
                    'statusLabel' => 'Активний',
                    'status' => 'active',
                    'medicationQty' => '10',
                    'programName' => 'Доступні ліки',
                    'createdAt' => '2026-08-20',
                    'periodLabel' => '20.08.2026-29.08.2026',
                    'dispensePeriodLabel' => '20.08.2026-29.08.2026',
                    'doctorName' => 'Петров І.І.',
                    'encounterId' => '1231-adsadas-aqeqe-casdda',
                    'basisLabel' => 'Плані лікування',
                    'carePlanId' => '1231-adsadas-aqeqe-casdda',
                ],
                [
                    'id' => 'fake-2',
                    'uuid' => 'fake-uuid-2',
                    'requestNumber' => '0000-KQR5-A0R4-NSF2',
                    'medicationName' => 'Парацетамол 500 мг, таблетки',
                    'statusBadge' => 'badge-red',
                    'statusLabel' => 'Завершений',
                    'status' => 'completed',
                    'medicationQty' => '0/10',
                    'programName' => 'Доступні ліки',
                    'createdAt' => '2026-05-02',
                    'periodLabel' => '02.05.2026-12.05.2026',
                    'dispensePeriodLabel' => '02.05.2026-12.05.2026',
                    'doctorName' => 'Іванов П.П.',
                    'encounterId' => '8492-fsdfdf-sdfsdf-sdfsdf',
                    'basisLabel' => 'Взаємодії',
                    'carePlanId' => null,
                ]
            ];
        }
    }

    public function applyFilters(): void
    {
        $this->loadMedicationRequests();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'filterStatus',
            'filterStartedAtFrom',
            'filterStartedAtTo',
            'filterEndedAtFrom',
            'filterEndedAtTo',
            'filterRequestNumber',
            'filterMedication',
            'filterInteractionId',
            'filterCarePlanId',
            'filterDoctor',
            'filterEpisodeId',
            'filterLegalEntity',
            'filterMedicalProgram',
            'filterCreatedAtFrom',
            'filterCreatedAtTo',
            'filterDispenseAvailableFrom',
            'filterDispenseAvailableTo',
        ]);
        $this->loadMedicationRequests();
    }

    public function render(): View
    {
        return view('livewire.person.records.medication-requests');
    }
}
