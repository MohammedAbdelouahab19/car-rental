<?php

namespace App\EventListener;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Reservation::class)]
class ReservationListener
{
    public function prePersist(Reservation $reservation): void
    {
        $reservation->setReference(
            sprintf('%s-%s-%s-%s',
                $reservation->getCar()->getBrand() ,
                $reservation->getStartDate()->format('Ymd'),
                $reservation->getEndDate()->format('Ymd'),
                $reservation->getDuration(),
            )
        );
    }
}
