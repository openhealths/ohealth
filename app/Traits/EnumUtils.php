<?php

declare(strict_types=1);

namespace App\Traits;

trait EnumUtils
{
    /**
     * Get all values.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all names.
     *
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get an associative array of names and values.
     *
     * @return array
     */
    public static function toArray(): array
    {
        return array_combine(self::names(), self::values());
    }

    /**
     * Get the options for a select input.
     *
     * @return array
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (object $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
