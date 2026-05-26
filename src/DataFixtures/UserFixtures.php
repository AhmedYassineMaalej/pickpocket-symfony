<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class UserFixtures extends Fixture
{
    public function __construct(
        #[Autowire('%env(DEV_USERNAME)%')]
        private string $username,
        #[Autowire('%env(DEV_PASSWORD)%')]
        private string $password,
    ) {}


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername($this->username);
        $user->setPassword($this->password);
        $manager->persist($user);
        $manager->flush();
    }
}
