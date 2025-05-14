<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Car;

interface CarHandlerInterface
{
    public function getById(int $id): Car;
}
