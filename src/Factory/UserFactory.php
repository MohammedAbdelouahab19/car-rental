<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Enum\RoleEnum;
use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        $f = self::faker();

        return [
            'firstName' => $f->firstName(),
            'lastName' => $f->lastName(),
            'password' => $f->password(),
            'roles' => [ RoleEnum::User->name ],
            'username' => $f->userName(),
            'passwordUpdatedAt' => new \DateTime(),
            'phoneNumber' => null,
            'createdAt' => new \DateTime(),
        ];
    }
}
