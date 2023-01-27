<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\PersonFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        Factory::delayFlush(function() {
            PersonFactory::createMany(2000, function () {
                return ['books' => BookFactory::new()->many(1, 20)];
            });
        });
    }
}
