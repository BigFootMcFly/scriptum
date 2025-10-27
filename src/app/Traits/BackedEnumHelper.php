<?php

namespace App\Traits;

trait BackedEnumHelper
{
    /**
     * Returns the names of all casee
     *
     * @return array<int, string> The names of all cases
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');

    }

    /**
     * Returnbs the values of all casee
     *
     * @return array<int, string> The values of all cases
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Converts the enum to array
     *
     * @return array<string, string> The key => value pairs of the cases
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    /**
     * Converts the enum to array with lower key values
     *
     * @return array<string, string> The strtolower(key) => value pairs of the cases
     *
     * @todo this was a temporary fix, refactor it!
     */
    public static function toSelectOptions(): array
    {
        return array_change_key_case(self::toArray(), CASE_LOWER);
    }
}
