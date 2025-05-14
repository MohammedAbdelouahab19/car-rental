<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ReservationHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class GetUserReservations extends AbstractController
{
    public function __construct(
        private readonly ReservationHandlerInterface $reservationHandler,
    ) {
    }

    public function __invoke(int $tenantId): array
    {
        if ($this->getUser()->getId() !== $tenantId) {
            throw new NotFoundHttpException('Not found.');
        }

        return $this->reservationHandler->getReservationsByTenant($tenantId);
    }
}
