<?php

declare(strict_types=1);

namespace App\Entity\Enum;

enum RoleEnum: string
{
    case User = 'ROLE_USER';

    public function name(): string
    {
        return match ($this) {
            self::User => 'User',
        };
    }
}
