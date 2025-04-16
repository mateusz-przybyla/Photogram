<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $image = null;

  #[ORM\Column(length: 500, nullable: true)]
  private ?string $description = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $created = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $location = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getImage(): ?string
  {
    return $this->image;
  }

  public function setImage(string $image): static
  {
    $this->image = $image;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;

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

  public function getLocation(): ?string
  {
    return $this->location;
  }

  public function setLocation(?string $location): static
  {
    $this->location = $location;

    return $this;
  }
}
