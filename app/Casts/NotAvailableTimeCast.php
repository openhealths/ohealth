<?php

declare(strict_types=1);

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class NotAvailableTimeCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  mixed  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     */
    public function get($model, string $key, mixed $value, array $attributes): mixed
    {
        $data = json_decode($value, true);

        if (!is_array($data)) {
            return $data;
        }

        return array_map(function ($item) {
            if (isset($item['during'])) {
                $item['during']['start'] = Carbon::parse($item['during']['start'])->format('Y-m-d');
                $item['during']['end'] = Carbon::parse($item['during']['end'])->format('Y-m-d');
            }

            return $item;
        }, $data);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  mixed  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     */
    public function set($model, string $key, mixed $value, array $attributes): bool|string
    {
        if (!is_array($value)) {
            return json_encode($value);
        }

        return json_encode(array_map(function ($item) {
            if (isset($item['during'])) {
                $item['during']['start'] = Carbon::parse($item['during']['start'])->format('Y-m-d');
                $item['during']['end'] = Carbon::parse($item['during']['end'])->format('Y-m-d');
            }

            return $item;
        }, $value));
    }
}
