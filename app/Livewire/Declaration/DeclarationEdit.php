<?php

declare(strict_types=1);

namespace App\Livewire\Declaration;

use App\Models\DeclarationRequest;
use App\Models\LegalEntity;

class DeclarationEdit extends DeclarationComponent
{
    public function mount(LegalEntity $legalEntity, int $patientId, DeclarationRequest $declarationRequest): void
    {
        $this->baseMount($patientId);
        $this->declarationRequestId = $declarationRequest->id;

        if (session('showSignModal')) {
            $this->showSignModal = true;
        }

        if ($declarationRequest->dataToBeSigned) {
            $this->printableContent = $declarationRequest->dataToBeSigned['content'];
            $this->dataToBeSigned = $declarationRequest->dataToBeSigned;
        }

        // Set form data
        $this->form->employeeId = $declarationRequest->load('employee:id,uuid')->employee->uuid;
        $this->form->authorizeWith = $declarationRequest->authorizeWith;

        $this->declarationRequestUuid = $declarationRequest->uuid ?? '';
    }
}
