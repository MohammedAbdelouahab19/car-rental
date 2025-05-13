<?php

namespace App\Story;

use App\Entity\Enum\UserEnum;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class TestStory extends Story
{

    public function build(): void
    {
        UserFactory::createOne([
                'firstName' => UserEnum::AbdelouahabMohammed->firstName(),
                'lastName' => UserEnum::AbdelouahabMohammed->lastName(),
                'username' => UserEnum::AbdelouahabMohammed->username(),
                'plainPassword' => 'azerty',
            ]
        );
    }
}
