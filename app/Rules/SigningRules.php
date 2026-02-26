<?php

declare(strict_types=1);

namespace App\Rules;

class SigningRules
{
    public static function rules(): array
    {
        return [
            'knedp' => ['required', 'string'],
            'password' => ['required', 'string'],
            'keyContainerUpload' => ['required', 'file', 'extensions:dat,pfx,pk8,zs2,jks,p7s']
        ];
    }
}
