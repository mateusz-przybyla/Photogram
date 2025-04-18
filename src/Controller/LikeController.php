<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class LikeController extends AbstractController
{
  #[Route('/like/{id}', name: 'app_like')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function like(
    Post $post,
    EntityManagerInterface $entityManager,
    Request $request
  ): Response {
    /** @var User $user */
    $user = $this->getUser();

    $post->addLikedBy($user);
    $entityManager->persist($post);
    $entityManager->flush();

    return $this->redirect($request->headers->get('referer')); // the last url
  }

  #[Route('/unlike/{id}', name: 'app_unlike')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function unlike(
    Post $post,
    EntityManagerInterface $entityManager,
    Request $request
  ): Response {
    /** @var User $user */
    $user = $this->getUser();

    $post->removeLikedBy($user);
    $entityManager->persist($post);
    $entityManager->flush();

    return $this->redirect($request->headers->get('referer'));
  }
}
