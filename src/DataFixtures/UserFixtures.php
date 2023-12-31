<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $pwd = 'test';

        $user = (new User())
            ->setEmail('user@user.fr')
            ->setRoles(['ROLE_USER'])
        ;
        $user->setPassword($this->passwordHasher->hashPassword($user, $pwd));
        $manager->persist($user);
        $this->addReference('user', $user);

        $user = (new User())
            ->setEmail('moderator@user.fr')
            ->setRoles(['ROLE_MODERATOR'])
        ;
        $user->setPassword($this->passwordHasher->hashPassword($user, $pwd));
        $manager->persist($user);
        $this->addReference('moderator', $user);

        $user = (new User())
            ->setEmail('admin@user.fr')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $user->setPassword($this->passwordHasher->hashPassword($user, $pwd));
        $manager->persist($user);
        $this->addReference('admin', $user);

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())->setEmail($faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, $pwd));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
