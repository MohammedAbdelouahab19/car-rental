<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\GetUserReservations;
use App\Entity\Enum\OperationEnum;
use App\Service\Validator;
use App\Validator\AvailableCar;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[GetCollection(
    order: ['startDate' => 'DESC', 'endDate' => 'ASC', 'id' => 'DESC'],
    normalizationContext: ['groups' => [OperationEnum::UserReservationListing->name]],
)]
#[Get(
    normalizationContext: ['groups' => [OperationEnum::ReservationDetail->name]],
)]
#[GetCollection(
    uriTemplate: '/users/{tenantId}/reservations',
    uriVariables: [
        'tenantId' => new Link(
            fromClass: User::class,
            fromProperty: 'reservations',
        )
    ],
    controller: GetUserReservations::class,
    normalizationContext: ['groups' => [OperationEnum::UserReservationListing->name]],
    read: false,
    provider: null,
    name: 'user_reservations',
)]
#[Post(
    denormalizationContext: ['groups' => [OperationEnum::ReservationCreate->name]],
    normalizationContext: ['groups' => [OperationEnum::ReservationDetail->name]],
)]
#[Put(
    denormalizationContext: ['groups' => [OperationEnum::ReservationUpdate->name]],
    normalizationContext: ['groups' => [OperationEnum::ReservationDetail->name]],
)]
#[Delete]
#[AvailableCar]
#[ORM\Entity]
class Reservation
{
    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::UserReservationListing->name,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\GreaterThanOrEqual('today', message: Validator::STARTING_DATE_GREATER_OR_EQ_THAN_TODAY)]
    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::UserReservationListing->name,
        OperationEnum::ReservationCreate->name,
        OperationEnum::ReservationUpdate->name,
    ])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startDate = null;

    #[Assert\GreaterThanOrEqual(propertyPath: 'startDate', message: Validator::END_DATE_GREATER_OR_EQ_THAN_START_DATE)]
    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::UserReservationListing->name,
        OperationEnum::ReservationCreate->name,
        OperationEnum::ReservationUpdate->name,
    ])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $endDate = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::UserReservationListing->name,
    ])]
    #[ORM\Column]
    private ?float $totalPrice = null;

    #[Groups([
        OperationEnum::ReservationDetail->name,
        OperationEnum::ReservationListing->name,
        OperationEnum::UserReservationListing->name,
    ])]
    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::UserReservationListing->name,
        OperationEnum::ReservationCreate->name,
        OperationEnum::ReservationUpdate->name,
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

    #[Groups([
        OperationEnum::ReservationDetail->name,
        OperationEnum::ReservationListing->name,
        OperationEnum::UserReservationListing->name,
    ])]
    public function getDuration($in = 'days'): int
    {
        return (int)($this->startDate->diff($this->endDate)->$in) + 1;
    }

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
