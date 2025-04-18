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

  #[ORM\ManyToOne(inversedBy: 'posts')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $author = null;

  /**
   * @var Collection<int, User>
   */
  #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'liked')]
  private Collection $likedBy;

  public function __construct()
  {
    $this->created = new DateTime();
    $this->comments = new ArrayCollection();
    $this->likedBy = new ArrayCollection();
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

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): static
  {
    $this->author = $author;

    return $this;
  }

  /**
   * @return Collection<int, User>
   */
  public function getLikedBy(): Collection
  {
    return $this->likedBy;
  }

  public function addLikedBy(User $likedBy): static
  {
    if (!$this->likedBy->contains($likedBy)) {
      $this->likedBy->add($likedBy);
    }

    return $this;
  }

  public function removeLikedBy(User $likedBy): static
  {
    $this->likedBy->removeElement($likedBy);

    return $this;
  }
}
