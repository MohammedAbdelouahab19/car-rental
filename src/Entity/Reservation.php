<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Enum\OperationEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[GetCollection(
    order: ['startDate' => 'DESC', 'endDate' => 'ASC', 'id' => 'DESC'],
    normalizationContext: ['groups' => [OperationEnum::ReservationListing->name]],
)]
#[Get(
    normalizationContext: ['groups' => [OperationEnum::ReservationDetail->name]],
)]
#[ORM\Entity]
class Reservation
{
    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startDate = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $endDate = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[ORM\Column]
    private ?float $totalPrice = null;

    #[Groups([
        OperationEnum::ReservationDetail->name,
        OperationEnum::ReservationListing->name,
    ])]
    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[Groups([
        OperationEnum::ReservationDetail->name,
        OperationEnum::ReservationListing->name,
    ])]

    public function getDuration($in = 'days'): int
    {
        return (int)($this->startDate->diff($this->endDate)->$in) + 1;
    }

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $tenant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getTenant(): ?User
    {
        return $this->tenant;
    }

    public function setTenant(?User $tenant): static
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }
}
