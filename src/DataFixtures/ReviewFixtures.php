<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $movie = (new Review())
                ->setOwner($this->getReference('user'))
                ->setMovie($this->getReference('movie'))
                ->setContent($faker->paragraph())
            ;
            $manager->persist($movie);
        }

        $users = $manager->getRepository(User::class)->findAll();
        $movies = $manager->getRepository(Movie::class)->findAll();
        for ($i = 0; $i < 50; $i++) {
            $movie = (new Review())
                ->setOwner($users[array_rand($users)])
                ->setMovie($movies[array_rand($movies)])
                ->setContent($faker->paragraph())
            ;
            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            MovieFixtures::class,
        ];
    }
}
