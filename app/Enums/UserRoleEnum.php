<?php

namespace App\Enums;

enum UserRoleEnum: int
{
    case USER = 1;

    case ADMIN = 2;

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
