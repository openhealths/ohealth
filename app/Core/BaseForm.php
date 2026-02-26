<?php

declare(strict_types=1);

namespace App\Core;

use App\Rules\SigningRules;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class BaseForm extends Form
{
    public string $knedp;

    public TemporaryUploadedFile $keyContainerUpload;

    public string $password;

    public function signingRules(): array
    {
        return SigningRules::rules();
    }
}
