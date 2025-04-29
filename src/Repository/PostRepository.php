<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Post::class);
  }

  public function findAllPostsByNewest(): array
  {
    return $this->findAllQuery()
      ->getQuery()
      ->getResult();
  }

  public function findAllWithComments(): array
  {
    return $this->findAllQuery(
      withComments: true
    )->getQuery()->getResult();
  }

  public function findAllByAuthor(int | User $author): array
  {
    return $this->findAllQuery()
      ->where('p.author = :author')
      ->setParameter(
        'author',
        $author instanceof User ? $author->getId() : $author
      )
      ->getQuery()
      ->getResult();
  }

  public function findAllByAuthors(Collection | array $authors): array
  {
    return $this->findAllQuery(
      withComments: true,
      withLikes: true,
      withAuthors: true,
      withProfiles: true
    )->where('p.author IN (:authors)')
      ->setParameter(
        'authors',
        $authors
      )
      ->getQuery()
      ->getResult();
  }

  private function findAllQuery(
    bool $withComments = false,
    bool $withLikes = false,
    bool $withAuthors = false,
    bool $withProfiles = false,
  ): QueryBuilder {
    $query = $this->createQueryBuilder('p');

    if ($withComments) {
      $query->leftJoin('p.comments', 'c')
        ->addSelect('c');
    }

    if ($withLikes) {
      $query->leftJoin('p.likedBy', 'l')
        ->addSelect('l');
    }

    if ($withAuthors || $withProfiles) {
      $query->leftJoin('p.author', 'a')
        ->addSelect('a');
    }

    if ($withProfiles) {
      $query->leftJoin('a.userProfile', 'up')
        ->addSelect('up');
    }

    return $query->orderBy('p.created', 'DESC');
  }

  //    /**
  //     * @return Post[] Returns an array of Post objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('p.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Post
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
