<?php

declare(strict_types=1);

namespace App\Validator;

use App\Service\Validator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AvailableCar extends Constraint
{
    public string $message = Validator::CAR_NOT_AVAILABLE_IN_THIS_PERIOD;

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
