<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Enum\OperationEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[GetCollection(
    order: ['id' => 'DESC'],
    normalizationContext: ['groups' => [OperationEnum::CarListing->name]],
)]
#[Get(
    normalizationContext: ['groups' => [OperationEnum::CarDetail->name]],
)]
#[ORM\Entity]
class Car
{
    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::CarListing->name,
        OperationEnum::CarDetail->name,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::CarListing->name,
        OperationEnum::CarDetail->name,
    ])]
    #[ORM\Column]
    private ?string $title = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::CarListing->name,
        OperationEnum::CarDetail->name,
    ])]
    #[ORM\Column(length: 50)]
    private ?string $brand = null;

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
        OperationEnum::CarListing->name,
        OperationEnum::CarDetail->name,
    ])]
    #[ORM\Column]
    private ?float $price = null;

    #[Groups([
        OperationEnum::CarListing->name,
        OperationEnum::CarDetail->name,
    ])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'car')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setCar($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCar() === $this) {
                $reservation->setCar(null);
            }
        }

        return $this;
    }
}
