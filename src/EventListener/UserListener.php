<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserListener
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public function prePersist(User $user, PrePersistEventArgs $event): void
    {
        $plainPassword = $user->getPlainPassword();

        if ($plainPassword) {
            $hashedPassword = $this->passwordEncoder->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $user->eraseCredentials();
        }
    }
}
