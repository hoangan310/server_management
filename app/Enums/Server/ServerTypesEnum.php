<?php

namespace App\Enums\Server;

enum ServerTypesEnum
{
    case VPS;
    case DEDICATED;
    case CLOUD;

    public function label(): string
    {
        return match ($this) {
            self::VPS => 'VPS',
            self::DEDICATED => 'Dedicated',
            self::CLOUD => 'Cloud',
        };
    }
}
