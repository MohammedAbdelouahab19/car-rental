<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Reservation;
use App\Service\ReservationHandlerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Reservation::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Reservation::class)]
readonly class ReservationListener
{
    public function __construct(
        private ReservationHandlerInterface $reservationHandler,
        private Security $security,
    ) {
    }

    public function prePersist(Reservation $reservation): void
    {
        $reservation->setReference($this->reservationHandler->generateReferenceFromReservation($reservation));
        $reservation->setTenant($this->security->getUser());
        $reservation->setTotalPrice($this->reservationHandler->calculateTotalPrice($reservation));
    }

    public function preUpdate(Reservation $reservation): void
    {
        $reservation->setReference($this->reservationHandler->generateReferenceFromReservation($reservation));
        $reservation->setTenant($this->security->getUser());
        $reservation->setTotalPrice($this->reservationHandler->calculateTotalPrice($reservation));
    }
}
