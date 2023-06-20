<?php

namespace QueSoft\Ntak\Traits;

trait EnumToArray
{
    /**
     * names
     *
     * @return array
     */
    public static function names(): array
    {
        return array_keys(self::toArray());
    }

    /**
     * values
     *
     * @return array
     */
    public static function cases(): array
    {
        return array_values(self::toArray());
    }

    /**
     * array
     *
     * @return array
     */
    public static function array(): array
    {
        return array_combine(self::cases(), self::names());
    }

    /**
     * random
     *
     * @return mixed
     */
    public static function random()
    {
        return self::values()[array_rand(self::values())];
    }
}
