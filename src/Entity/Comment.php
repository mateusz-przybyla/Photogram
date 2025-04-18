<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 500)]
  #[Assert\NotBlank()]
  #[Assert\Length(max: 500, maxMessage: 'Comment is too long, 500 characters is the maximum.')]
  private ?string $text = null;

  #[ORM\ManyToOne(inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Post $post = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getText(): ?string
  {
    return $this->text;
  }

  public function setText(string $text): static
  {
    $this->text = $text;

    return $this;
  }

  public function getPost(): ?Post
  {
    return $this->post;
  }

  public function setPost(?Post $post): static
  {
    $this->post = $post;

    return $this;
  }
}
