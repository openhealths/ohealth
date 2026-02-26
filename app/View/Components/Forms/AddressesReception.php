<?php

declare(strict_types=1);

namespace App\View\Components\Forms;

use App\Rules\Zip;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;

class AddressesReception extends Addresses
{
    /**
     * Create a new component instance.
     */
    public function __construct($address, $districts, $settlements, $streets, $class, $readonly = false)
    {
        parent::__construct($address, $districts, $settlements, $streets, $class, $readonly);
    }

    public static function getAddressRules(array $address): array
    {
        return [
            'receptionAddress.area' => ['required', 'string'],
            'receptionAddress.region' => [
                'sometimes',
                Rule::requiredIf(function () use ($address) {
                    if (empty($address['area'])) {
                        return true;
                    }

                    return $address['area'] !== 'М.КИЇВ';
                }),
            ],
            'receptionAddress.settlementType' => ['required', 'string'],
            'receptionAddress.settlement' => ['required', 'string'],
            'receptionAddress.settlementId' => ['required', 'string'],
            'receptionAddress.streetType' => ['nullable', 'string'],
            'receptionAddress.street' => ['nullable', 'string'],
            'receptionAddress.building' => ['nullable', 'string'],
            'receptionAddress.apartment' => ['nullable', 'string'],
            'receptionAddress.zip' => ['nullable', 'string', new Zip()],
        ];
    }

    public static function getAddressMessages(): array
    {
        return [
            'receptionAddress.area' => __("Поле 'Область' є обов'язковим"),
            'receptionAddress.region.required' => __("Поле 'Район' є обов’язковим"),
            'receptionAddress.settlementType' => __("Поле 'Тип населеного пункту' є обов'язковим"),
            'receptionAddress.settlement' => __("Поле 'Населений пункт' є обов'язковим"),
            'receptionAddress.building' => __("Неправильний формат номеру будинка"),
            'receptionAddress.apartment' => __("Неправильний формат номеру квартири"),
            'receptionAddress.zip' => __("Неправильний формат поштового індекса"),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.forms.addresses-reception');
    }
}
