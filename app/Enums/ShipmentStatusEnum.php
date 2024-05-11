<?php

namespace App\Enums;

enum ShipmentStatusEnum: int
{
    case PENDING = 1; // house keepig mode
    case IN_PROGRESS = 2; // item has left warehouse
    case SHIPPED = 3; // item is shipped, at the warehouse based on business requirement
    case DELIVERED = 4; // item delivered to user
    case COMPLETED = 5; // process is completed

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
