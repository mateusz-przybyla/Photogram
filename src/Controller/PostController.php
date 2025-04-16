<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PostController extends AbstractController
{
  #[Route('/post/dashboard', name: 'app_post_dashboard')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function dashboard(PostRepository $posts): Response
  {
    return $this->render('post/dashboard.html.twig', [
      'posts' => $posts->findAll()
    ]);
  }

  #[Route('/post/{post}', name: 'app_post_show')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function showOne(Post $post): Response
  {
    return $this->render('post/show.html.twig', [
      'post' => $post,
    ]);
  }

  #[Route('/post/add', name: 'app_post_add')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function addPost(): Response
  {
    return $this->render('post/dashboard.html.twig', [
      'controller_name' => 'PostController',
    ]);
  }
}
