<?php

declare(strict_types=1);

namespace App\Livewire\License;

use App\Models\LegalEntity;
use App\Models\License;
use Illuminate\View\View;
use Livewire\Component;

class LicenseView extends Component
{
    protected License $license;

    public function mount(LegalEntity $legalEntity, License $license): void
    {
        $this->license = $license;
    }

    public function render(): View
    {
        return view('livewire.license.license-view')->with(['license' => $this->license]);
    }
}
