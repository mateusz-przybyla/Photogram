<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
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
    $user1->setIsVerified(true);
    $manager->persist($user1);

    $user2 = new User();
    $user2->setEmail('test2@test.com');
    $user2->setPassword($this->userPasswordHasher->hashPassword($user2, 'aaaaaaaa'));
    $user2->setIsVerified(true);
    $manager->persist($user2);

    $post1 = new Post();
    $post1->setImage('aaa1.jpg');
    $post1->setDescription('Hello1');
    $post1->setLocation('Katowice1');
    $post1->setCreated(new DateTime());
    $post1->setAuthor($user1);
    $manager->persist($post1);

    $post2 = new Post();
    $post2->setImage('aaa2.jpg');
    $post2->setDescription('Hello2');
    $post2->setLocation('Katowice2');
    $post2->setCreated(new DateTime());
    $post2->setAuthor($user2);
    $manager->persist($post2);

    $manager->flush();
  }
}
