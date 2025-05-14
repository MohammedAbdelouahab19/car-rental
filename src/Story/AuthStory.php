<?php

declare(strict_types=1);

namespace App\Story;

use App\Entity\Enum\UserEnum;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class AuthStory extends Story
{
    public function build(): void
    {
        try {
            UserFactory::new()
                ->many(count(UserEnum::cases()))
                ->create(function ($i) {
                    $enum = UserEnum::cases()[$i - 1];

                    return [
                        'firstName' => $enum->firstName(),
                        'lastName' => $enum->lastName(),
                        'username' => $enum->username(),
                        'plainPassword' => 'azerty',
                        'roles' => [$enum->role()->value],
                        'passwordUpdatedAt' => new \DateTime(), // Set this to a valid datetime
                        'phoneNumber' => null, // Or set a value here if needed
                        'createdAt' => new \DateTime(), // Set this to the current time or a valid datetime
                        'updatedAt' => new \DateTime(), // Set this to the current time or a valid datetime
                    ];
                });
        }catch (\Exception $exception) {
            dd($exception);
        }
    }
}
