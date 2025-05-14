<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Repository\CarRepository;

readonly class CarHandler implements CarHandlerInterface
{
    public function __construct(private CarRepository $repository)
    {
    }

    public function getById(int $id): Car
    {
        return $this->repository->find($id);
    }
}
