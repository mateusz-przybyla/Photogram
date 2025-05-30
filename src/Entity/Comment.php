<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
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

  #[ORM\ManyToOne(inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $author = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $created = null;

  public function __construct()
  {
    $this->created = new DateTime();
  }

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

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): static
  {
    $this->author = $author;

    return $this;
  }

  public function getCreated(): ?\DateTimeInterface
  {
    return $this->created;
  }

  public function setCreated(\DateTimeInterface $created): static
  {
    $this->created = $created;

    return $this;
  }
}
