<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\PersonFactory;
use App\Factory\ReviewFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        Factory::delayFlush(function() {
            PersonFactory::createMany(2000, function () {
                return ['books' => BookFactory::new([
                    'reviews' => ReviewFactory::new()->many(0, 5),
                ])->many(1, 20)];
            });
        });
    }
}
