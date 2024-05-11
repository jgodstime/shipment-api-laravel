<?php

namespace App\Enums;

enum RedisEnum: string
{
    case WAREHOUSES = 'warehouses';
    case TRACKING_NUMBER = 'tracking_number_';

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum ".self::class);
    }

    public static function tryFromName(string $name): ?self
    {
        try {
            return self::fromName($name);
        } catch (\ValueError $error) {
            return null;
        }
    }
}
