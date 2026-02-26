<?php

declare(strict_types=1);

namespace App\Livewire\Person\Records\Forms;

use App\Enums\Person\AuthenticationMethod;
use Livewire\Form;

class PersonForm extends Form
{
    public array $authenticationMethod;

    public string $action;

    public function rulesForDeactivate(): array
    {
        return [
            'action' => ['required', 'string', 'in:DEACTIVATE'],
            'authenticationMethod' => ['required', 'array'],
            'authenticationMethod.id' => ['required', 'uuid']
        ];
    }

    public function rulesForInsert(): array
    {
        return [
            'action' => ['required', 'string', 'in:INSERT'],
            'authenticationMethod' => ['required', 'array'],
            'authenticationMethod.type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $currentTypes = collect($this->component->authenticationMethods)->pluck('type');

                    if ($currentTypes->contains($value)) {
                        $fail(__('patients.errors.authMethod.duplicate'));

                        return;
                    }

                    $mutuallyExclusive = [
                        AuthenticationMethod::OTP->value => AuthenticationMethod::OFFLINE->value,
                        AuthenticationMethod::OFFLINE->value => AuthenticationMethod::OTP->value
                    ];

                    if (isset($mutuallyExclusive[$value]) && $currentTypes->contains($mutuallyExclusive[$value])) {
                        $fail('patients.errors.authMethod.distinct');
                    }
                }
            ],
            'authenticationMethod.phoneNumber' => [
                'required_if:authenticationMethod.type,OTP',
                'regex:/^\+38[0-9]{10}$/'
            ],
            'authenticationMethod.value' => ['required_if:authenticationMethod.type,THIRD_PERSON', 'uuid'],
            'authenticationMethod.alias' => ['required_if:authenticationMethod.type,THIRD_PERSON', 'string', 'max:255']
        ];
    }

    public function rulesForUpdate(): array
    {
        return [
            'action' => ['required', 'string', 'in:UPDATE'],
            'authenticationMethod' => ['required', 'array'],
            'authenticationMethod.id' => ['required', 'uuid'],
            'authenticationMethod.alias' => ['nullable', 'uuid', 'max:255']
        ];
    }
}
