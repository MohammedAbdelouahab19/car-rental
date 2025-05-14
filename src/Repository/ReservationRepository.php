<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function isCarAvailable(Reservation $reservation,?int $reservationId): bool
    {
        $qb = $this->createQueryBuilder('r');

        $qb->where('r.car = :car')
            ->andWhere('
            (:startDate BETWEEN r.startDate AND r.endDate) OR
            (:endDate BETWEEN r.startDate AND r.endDate) OR
            (r.startDate BETWEEN :startDate AND :endDate)
        ')
            ->setParameter('car', $reservation->getCar())
            ->setParameter('startDate', $reservation->getStartDate())
            ->setParameter('endDate', $reservation->getEndDate());

        if ($reservationId) {
            $qb->andWhere('r.id != :id')
                ->setParameter('id', $reservationId);
        }

        return count($qb->getQuery()->getResult()) === 0;
    }
}
