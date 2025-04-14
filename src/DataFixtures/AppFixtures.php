<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}

  public function load(ObjectManager $manager): void
  {
    $user1 = new User();
    $user1->setEmail('test1@test.com');
    $user1->setPassword($this->userPasswordHasher->hashPassword($user1, 'aaaaaaaa'));
    $manager->persist($user1);
    $manager->flush();

    $user2 = new User();
    $user2->setEmail('test2@test.com');
    $user2->setPassword($this->userPasswordHasher->hashPassword($user2, 'aaaaaaaa'));
    $manager->persist($user2);
    $manager->flush();
  }
}
