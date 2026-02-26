<?php

declare(strict_types=1);

namespace App\Livewire\Contract;

use App\Models\Contracts\Contract;
use App\Models\LegalEntity;
use Livewire\Component;

class ContractShow extends Component
{
    public Contract $contract;

    //Laravel will automatically "inject" the already found Contract model
    public function mount(LegalEntity $legalEntity, Contract $contract): void
    {
        $this->contract = $contract;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.contract.contract-show', [
            'contract' => $this->contract,
            'data' => $this->contract->data ?? [],
        ]);
    }
}
