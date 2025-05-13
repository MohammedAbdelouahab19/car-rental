<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(private readonly DevFixtures $devFixtures)
    {
    }

    public function load(ObjectManager $manager): void
    {
        if($_ENV['APP_ENV'] === 'dev') {
            $this->devFixtures->load($manager);
        }

        $manager->flush();
    }
}
