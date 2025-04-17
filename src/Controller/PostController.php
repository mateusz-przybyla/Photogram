<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

  #[Route('/post/add', name: 'app_post_add', priority: 2)]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function addPost(
    Request $request,
    SluggerInterface $slugger,
    EntityManagerInterface $entityManager
  ): Response {
    $form = $this->createForm(PostType::class, new Post());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $post = $form->getData();
      //$post->setAuthor($this->getUser());

      $postImageFile = $form->get('postImage')->getData();

      if ($postImageFile) {
        $originalFilename = pathinfo(
          $postImageFile->getClientOriginalName(),
          PATHINFO_FILENAME
        );
        $safeFilename = $slugger->slug($originalFilename);
        $newFileName = $safeFilename . '-' . uniqid() . '.' . $postImageFile->guessExtension();

        try {
          $postImageFile->move(
            $this->getParameter('posts_directory'),
            $newFileName
          );

          $post->setImage($newFileName);
        } catch (FileException $e) {
          $this->addFlash('error', 'An error occurred while uploading the image. Please try again.');
          return $this->render('post/add.html.twig', [
            'form' => $form
          ]);
        }
      }

      $entityManager->persist($post);
      $entityManager->flush();

      $this->addFlash('success', 'Your post has been added.');

      return $this->redirectToRoute('app_post_dashboard');
    }

    return $this->render('post/add.html.twig', [
      'form' => $form
    ]);
  }
}
