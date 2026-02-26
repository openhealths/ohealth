<?php

declare(strict_types=1);

namespace App\Livewire\Person;

use App\Models\LegalEntity;
use Illuminate\View\View;

class PersonCreate extends PersonComponent
{
    public function mount(LegalEntity $legalEntity): void
    {
        $this->baseMount();
    }

    public function render(): View
    {
        return view('livewire.person.person-create');
    }
}
