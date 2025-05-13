<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\CarFactory;
use App\Factory\ReservationFactory;
use Zenstruck\Foundry\Story;

final class DevStory extends Story
{
    const int CAR_COUNT = 25;
    const int RESERVATION_COUNT = 10;

    public function build(): void
    {
        AuthStory::load();

        CarFactory::createMany(self::CAR_COUNT);
        ReservationFactory::createMany(self::RESERVATION_COUNT);
    }
}
