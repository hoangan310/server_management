<?php

namespace App\Enums\Server;

enum ServerStatusEnum
{
    case ACTIVE;
    case INACTIVE;
    case MAINTENANCE;
    case SUSPENDED;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::MAINTENANCE => 'Maintenance',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function variant(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
            self::MAINTENANCE => 'warning',
            self::SUSPENDED => 'danger',
        };
    }
}
