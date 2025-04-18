<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use Symfony\Component\Validator\Constraints as Assert;

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
  #[Assert\Length(max: 500, maxMessage: 'Description is too long, 500 characters is the maximum.')]
  private ?string $description = null;

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  private ?\DateTimeInterface $created = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Assert\Length(max: 255, maxMessage: 'Location is too long, 255 characters is the maximum.')]
  private ?string $location = null;

  /**
   * @var Collection<int, Comment>
   */
  #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post', orphanRemoval: true, /*cascade: ['persist']*/)]
  private Collection $comments;

  public function __construct()
  {
    $this->created = new DateTime();
    $this->comments = new ArrayCollection();
  }

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

  /**
   * @return Collection<int, Comment>
   */
  public function getComments(): Collection
  {
    return $this->comments;
  }

  public function addComment(Comment $comment): static
  {
    if (!$this->comments->contains($comment)) {
      $this->comments->add($comment);
      $comment->setPost($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): static
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getPost() === $this) {
        $comment->setPost(null);
      }
    }

    return $this;
  }
}
