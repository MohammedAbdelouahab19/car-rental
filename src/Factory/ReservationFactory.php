<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Reservation;
use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use function Zenstruck\Foundry\lazy;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class ReservationFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Reservation::class;
    }

    protected function defaults(): array|callable
    {
        $f = self::faker();
        $startDate = $f->dateTimeInInterval($f->dateTime());
        $endDate = $f->dateTimeInInterval($startDate);

        return [
            'tenant' => lazy(fn() => UserFactory::randomOrCreate()),
            'car' => lazy(fn() => CarFactory::randomOrCreate()),
            'startDate' => $startDate,
            'endDate' =>  $endDate,
            'totalPrice' => $f->numberBetween(100, 1000),
        ];
    }
}
