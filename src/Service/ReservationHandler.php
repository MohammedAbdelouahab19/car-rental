<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;

readonly class ReservationHandler implements ReservationHandlerInterface
{
    public function __construct(
        private ReservationRepository $reservationRepository,
    ) {
    }

    public function getReservationsByTenant(int|User $tenant): array
    {
        return $this->reservationRepository->findBy(['tenant' => $tenant]);
    }

    public function generateReferenceFromReservation(Reservation $reservation): string
    {
        return sprintf('%s-%s-%s-%s',
            $reservation->getCar()->getBrand(),
            $reservation->getStartDate()->format('dmY'),
            $reservation->getEndDate()->format('dmY'),
            $reservation->getDuration(),
        );
    }

    public function calculateTotalPrice(Reservation $reservation): float
    {
        return $reservation->getCar()->getPrice() * $reservation->getDuration();
    }

    public function isCarAvailable(Reservation $reservation, ?int $reservationId): bool
    {
        return $this->reservationRepository->isCarAvailable($reservation, $reservationId);
    }
}
