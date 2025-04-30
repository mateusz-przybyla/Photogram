<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
  public function testSettersAndGetters(): void
  {
    $post = new Post();

    $post->setDescription('Opis posta');
    $post->setLocation('Warszawa');
    $post->setImage('obrazek.jpg');

    $this->assertSame('Opis posta', $post->getDescription());
    $this->assertSame('Warszawa', $post->getLocation());
    $this->assertSame('obrazek.jpg', $post->getImage());
    $this->assertInstanceOf(\DateTimeInterface::class, $post->getCreated());
  }

  public function testAuthorAssociation(): void
  {
    $post = new Post();
    $user = new User();

    $post->setAuthor($user);

    $this->assertSame($user, $post->getAuthor());
  }

  public function testAddAndRemoveComment(): void
  {
    $post = new Post();
    $comment = new Comment();

    $this->assertCount(0, $post->getComments());

    $post->addComment($comment);
    $this->assertCount(1, $post->getComments());
    $this->assertSame($post, $comment->getPost());

    $post->removeComment($comment);
    $this->assertCount(0, $post->getComments());
    $this->assertNull($comment->getPost());
  }

  public function testLikeFunctionality(): void
  {
    $post = new Post();
    $user = new User();

    $this->assertCount(0, $post->getLikedBy());

    $post->addLikedBy($user);
    $this->assertCount(1, $post->getLikedBy());
    $this->assertTrue($post->getLikedBy()->contains($user));

    $post->removeLikedBy($user);
    $this->assertCount(0, $post->getLikedBy());
  }
}
