<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<User, User>
 */
final readonly class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    /**
     * @param User $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        $plainPassword = $data->getPlainPassword();

        if ($plainPassword) {
            $hashedPassword = $this->passwordHasher->hashPassword($data, $plainPassword);
            $data->setPassword($hashedPassword);
            $data->eraseCredentials();
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
