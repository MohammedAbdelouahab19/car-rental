<?php

declare (strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Enum\OperationEnum;
use App\Entity\Trait\Timestamp\HasTimestamps;
use App\Service\Validator;
use App\State\AuthenticatedUserProvider;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(normalizationContext: ['jsonld_embed_context' => true])]
#[Get(requirements: ['id' => "(?!me).+"])]
#[Get(
    uriTemplate: '/users/me',
    normalizationContext: ['groups' => [OperationEnum::USER_AUTH]],
    provider: AuthenticatedUserProvider::class,
)]
#[GetCollection(
    normalizationContext: ['groups' => [OperationEnum::UserListing->name]]
)]
#[ApiFilter(filterClass: OrderFilter::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'user_unique', columns: ['username'])]
#[UniqueEntity(fields: 'username', message: Validator::UNIQUE_ENTITY)]
#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use HasTimestamps;

    #[Groups([
        OperationEnum::USER_AUTH,
        OperationEnum::UserListing->name,
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[Groups([
        OperationEnum::USER_AUTH,
        OperationEnum::UserCreate->name,
        OperationEnum::UserDetail->name,
        OperationEnum::UserListing->name,
    ])]
    #[Assert\NotBlank(message: Validator::NOT_BLANK)]
    #[ORM\Column]
    private ?string $firstName = null;

    #[Groups([
        OperationEnum::USER_AUTH,
        OperationEnum::UserCreate->name,
        OperationEnum::UserDetail->name,
        OperationEnum::UserListing->name,
    ])]
    #[Assert\NotBlank(message: Validator::NOT_BLANK)]
    #[ORM\Column]
    private ?string $lastName = null;

    #[Groups([
        OperationEnum::USER_AUTH,
        OperationEnum::UserCreate->name,
        OperationEnum::UserDetail->name,
        OperationEnum::UserListing->name,
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    #[Assert\NotBlank(message: Validator::NOT_BLANK)]
    #[ORM\Column]
    private ?string $username = null;

    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[Assert\NotBlank(groups: [OperationEnum::UserCreate->name])]
    #[Groups([OperationEnum::UserCreate->name])]
    private ?string $plainPassword = null;

    #[Groups([
        OperationEnum::USER_AUTH,
        OperationEnum::UserDetail->name,
        OperationEnum::UserListing->name,
    ])]
    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'change', field: 'password')]
    private ?\DateTime $passwordUpdatedAt = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[Groups([
        OperationEnum::USER_AUTH,
        OperationEnum::UserCreate->name,
        OperationEnum::UserDetail->name,
        OperationEnum::UserListing->name,
    ])]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phoneNumber = null;

    #[Groups([
        OperationEnum::UserListing->name,
        OperationEnum::UserDetail->name,
    ])]
    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTime $createdAt = null;

    #[Groups([
        OperationEnum::UserListing->name,
        OperationEnum::UserDetail->name,
    ])]
    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable]
    private ?\DateTime $updatedAt;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'tenant')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    #[Groups([
        OperationEnum::USER_AUTH,
        OperationEnum::UserListing->name,
    ])]

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups([
        OperationEnum::ReservationListing->name,
        OperationEnum::ReservationDetail->name,
    ])]
    public function getName(): string
    {
        return strtoupper($this->getLastName()) . ' ' . lcfirst($this->getFirstName());
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPasswordUpdatedAt(): ?\DateTimeInterface
    {
        return $this->passwordUpdatedAt;
    }

    public function setPasswordUpdatedAt(?\DateTimeInterface $passwordUpdatedAt): static
    {
        $this->passwordUpdatedAt = $passwordUpdatedAt;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
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
            $reservation->setTenant($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTenant() === $this) {
                $reservation->setTenant(null);
            }
        }

        return $this;
    }
}
