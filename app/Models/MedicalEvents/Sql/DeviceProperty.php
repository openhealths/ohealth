<?php

declare(strict_types=1);

namespace App\Models\MedicalEvents\Sql;

use App\Core\Arr;
use Eloquence\Behaviours\HasCamelCasing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeviceProperty extends Model
{
    use HasCamelCasing;

    protected $fillable = [
        'device_id',
        'code_id'
    ];

    protected $hidden = [
        'id',
        'device_id',
        'code_id',
        'created_at',
        'updated_at'
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function code(): BelongsTo
    {
        return $this->belongsTo(CodeableConcept::class, 'code_id');
    }

    public function value(): HasOne
    {
        return $this->hasOne(Value::class);
    }

    /**
     * Build a readable value of the property, whichever of the value types it carries. A stored property keeps
     * its value under the `value` relation, while the eHealth API returns it on the property itself.
     * The coding system of a codeable concept value is free-form, so the value is shown as it was recorded.
     *
     * @param  self|array  $property
     * @return string
     */
    public static function displayValue(self|array $property): string
    {
        $property = $property instanceof self ? Arr::toCamelCase($property->toArray()) : $property;

        $codeableConcept = data_get($property, 'valueCodeableConcept')
            ?? data_get($property, 'value.valueCodeableConcept');

        if (is_array($codeableConcept)) {
            return data_get($codeableConcept, 'text') ?: (string) data_get($codeableConcept, 'coding.0.code');
        }

        $quantity = data_get($property, 'valueQuantity') ?? data_get($property, 'value.valueQuantity');

        if (is_array($quantity)) {
            return self::displayQuantity($quantity);
        }

        $range = data_get($property, 'valueRange') ?? data_get($property, 'value.valueRange');

        if (is_array($range)) {
            return self::displayQuantity(data_get($range, 'low', []))
                . ' — ' . self::displayQuantity(data_get($range, 'high', []));
        }

        $boolean = data_get($property, 'valueBoolean') ?? data_get($property, 'value.valueBoolean');

        if ($boolean !== null) {
            return $boolean ? __('forms.yes') : __('forms.no');
        }

        $integer = data_get($property, 'valueInteger') ?? data_get($property, 'value.valueInteger');
        $string = data_get($property, 'valueString') ?? data_get($property, 'value.valueString');

        return (string) ($integer ?? $string ?? '-');
    }

    /**
     * Join the parts a quantity was given into a single readable value.
     *
     * @param  array  $quantity
     * @return string
     */
    private static function displayQuantity(array $quantity): string
    {
        $parts = [data_get($quantity, 'comparator'), data_get($quantity, 'value'), data_get($quantity, 'unit')];

        return implode(' ', array_filter($parts, static fn (mixed $part): bool => filled($part)));
    }
}
