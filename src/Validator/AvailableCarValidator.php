<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Reservation;
use App\Service\ReservationHandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class AvailableCarValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ReservationHandlerInterface $reservationHandler,
        private readonly RequestStack                $requestStack,
    ) {
    }

    /**
     * @param Reservation $reservation
     */
    public function validate($reservation, Constraint $constraint): void
    {
        if (!$reservation instanceof Reservation) {
            throw new UnexpectedValueException($reservation, Reservation::class);
        }

        if (!$constraint instanceof AvailableCar) {
            throw new UnexpectedValueException($constraint, AvailableCar::class);
        }

        $request = $this->requestStack->getCurrentRequest();
        $reservationId = ($request->attributes->get('id'));

        $isAvailable = $this->reservationHandler->isCarAvailable($reservation, (int)$reservationId);

        if (!$isAvailable) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('car')
                ->addViolation();
        }
    }
}
