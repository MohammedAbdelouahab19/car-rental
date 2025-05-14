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

            'passwordUpdatedAt' => new \DateTime(), // Set this to a valid datetime
            'phoneNumber' => null, // Or set a value here if needed
            'createdAt' => new \DateTime(), // Set this to the current time or a valid datetime
            'updatedAt' => new \DateTime(), // Set this to the current time or a valid datetime
        ];
    }
}
