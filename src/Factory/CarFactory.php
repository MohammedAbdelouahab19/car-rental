<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Car;
use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class CarFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Car::class;
    }

    protected function defaults(): array|callable
    {
        $f = self::faker();

        return [
            'title' => $f->text(maxNbChars: 100),
            'brand' => $f->text(maxNbChars: 10),
            'price' => $f->numberBetween(100, 1000),
            'description' => $f->text(),
        ];
    }
}
