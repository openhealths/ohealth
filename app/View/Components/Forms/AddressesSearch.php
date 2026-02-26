<?php

declare(strict_types=1);

namespace App\View\Components\Forms;

use App\Rules\Zip;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;

class AddressesSearch extends Addresses
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
            'address.area' => ['required', 'string'],
            'address.region' => [
                'sometimes',
                Rule::requiredIf(function () use ($address) {
                    if (empty($address['area'])) {
                        return true;
                    }

                    return $address['area'] !== 'М.КИЇВ';
                }),
            ],
            'address.settlementType' => ['required', 'string'],
            'address.settlement' => ['required', 'string'],
            'address.settlementId' => ['required', 'string'],
            'address.streetType' => ['nullable', 'string'],
            'address.street' => ['nullable', 'string'],
            'address.building' => ['nullable', 'string'],
            'address.apartment' => ['nullable', 'string'],
            'address.zip' => ['nullable', 'string', new Zip()],
        ];
    }

    public static function getAddressMessages(): array
    {
        return [
            'address.area' => __("Поле 'Область' є обов’язковим"),
            'address.region.required' => __("Поле 'Район' є обов’язковим"),
            'address.settlementType' => __("Поле 'Тип населеного пункту' є обов’язковим"),
            'address.settlement' => __("Поле 'Населений пункт' є обов’язковим"),
            'address.building' => __("Неправильний формат номеру будинка"),
            'address.apartment' => __("Неправильний формат номеру квартири"),
            'address.zip' => __("Неправильний формат поштового індекса"),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.forms.addresses-search');
    }
}
