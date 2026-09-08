<?php

declare(strict_types=1);

namespace App\Livewire\Person\Records;

use Illuminate\Contracts\View\View;

class PatientPrescriptionRequestView extends BasePatientComponent
{
    public string $requestId;

    public function mount(\App\Models\LegalEntity $legalEntity, ?\App\Models\Person\Person $person = null, ?\App\Models\Preperson $preperson = null, ?string $requestId = null): void
    {
        parent::mount($legalEntity, $person, $preperson);
        $this->requestId = $requestId ?? '';
    }

    public function render(): View
    {
        return view('livewire.person.records.patient-prescription-request-view');
    }
}
