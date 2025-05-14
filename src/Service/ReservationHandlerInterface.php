<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\User;

interface ReservationHandlerInterface
{
    public function getReservationsByTenant(int|User $tenant): array;
    public function generateReferenceFromReservation(Reservation $reservation): string;
    public function calculateTotalPrice(Reservation $reservation): float;
    public function isCarAvailable(Reservation $reservation, ?int $reservationId): bool;
}
