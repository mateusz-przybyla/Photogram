<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
  public function testCommentIsCreatedCorrectly(): void
  {
    $comment = new Comment();

    $text = 'This is a test comment.';
    $comment->setText($text);
    $this->assertSame($text, $comment->getText());

    $post = new Post();
    $comment->setPost($post);
    $this->assertSame($post, $comment->getPost());

    $author = new User();
    $comment->setAuthor($author);
    $this->assertSame($author, $comment->getAuthor());

    $this->assertInstanceOf(\DateTimeInterface::class, $comment->getCreated());
  }
}
