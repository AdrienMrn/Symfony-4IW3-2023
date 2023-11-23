<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $movie = (new Movie())
            ->setName('The Matrix')
            ->setDescription('Un film de science-fiction')
            ->setDuration(120);
        $manager->persist($movie);
        $this->addReference('movie', $movie);

        for ($i = 0; $i < 10; $i++) {
            $movie = (new Movie())
                ->setName($faker->sentence(3))
                ->setDescription($faker->paragraph())
                ->setDuration($faker->numberBetween(60, 180))
            ;
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
