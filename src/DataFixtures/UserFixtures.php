<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $pwd = 'test';

        $user = (new User())
            ->setEmail('user@user.fr')
            ->setPassword($pwd)
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user);
        $this->addReference('user', $user);

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setEmail($faker->email())
                ->setPassword($pwd)
                ->setRoles(['ROLE_USER'])
            ;
            $manager->persist($user);
        }

        $manager->flush();
    }
}
