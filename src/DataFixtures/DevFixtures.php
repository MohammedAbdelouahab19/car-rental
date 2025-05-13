<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Story\DevStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DevFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DevStory::load();
    }
}
