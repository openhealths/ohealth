<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AuthenticationCard extends Component
{
    public function __construct(public bool $showLogo = true)
    {
    }

    public function render(): View
    {
        return view('components.authentication-card');
    }
}
