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
        $startDate = $f->dateTimeBetween('-1 month', 'now');
        $endDate = $f->dateTimeBetween($startDate->format('Y-m-d H:i:s'), '+1 week');

        return [
            'tenant' => lazy(fn() => UserFactory::randomOrCreate()),
            'car' => lazy(fn() => CarFactory::randomOrCreate()),
            'startDate' => $startDate,
            'endDate' =>  $endDate,
            'totalPrice' => $f->numberBetween(100, 1000),
            'reference' => $f->title(),
        ];
    }
}
