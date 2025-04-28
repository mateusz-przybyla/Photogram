<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\ImageUploader;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class PostController extends AbstractController
{
  #[Route('/post/main', name: 'app_post_main')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function main(PostRepository $posts): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    return $this->render('post/main.html.twig', [
      'posts' => $posts->findAllByAuthors(
        $user->getFollows()
      )
    ]);
  }

  #[Route('/post/explore', name: 'app_post_explore')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function explore(PostRepository $posts): Response
  {
    return $this->render('post/explore.html.twig', [
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
    ImageUploader $imageUploader,
    EntityManagerInterface $entityManager
  ): Response {
    $form = $this->createForm(PostType::class, new Post());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $post = $form->getData();
      $post->setAuthor($this->getUser());

      $postImageFile = $form->get('postImage')->getData();

      if ($postImageFile) {
        try {
          $newFileName = $imageUploader->upload($postImageFile, $this->getParameter('posts_images_directory'));
          $post->setImage($newFileName);
        } catch (FileException $e) {
          $this->addFlash('error', 'An error occurred while uploading the image. Please try again.');
          return $this->render('post/add.html.twig', [
            'form' => $form
          ]);
        }

        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success', 'Your post has been added.');
        return $this->redirectToRoute('app_post_main');
      }
    }

    return $this->render('post/add.html.twig', [
      'form' => $form
    ]);
  }

  #[Route('/post/{post}/comment', name: 'app_post_comment')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function addComment(
    Post $post,
    Request $request,
    EntityManagerInterface $entityManager
  ): Response {
    $form = $this->createForm(CommentType::class, new Comment());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $comment = $form->getData();
      $comment->setPost($post);
      $comment->setAuthor($this->getUser());

      $entityManager->persist($comment);
      $entityManager->flush();

      $this->addFlash('success', 'Your comment has been added.');

      return $this->redirectToRoute(
        'app_post_show',
        [
          'post' => $post->getId()
        ]
      );
    }

    return $this->render('post/comment.html.twig', [
      'form' => $form,
      'post' => $post
    ]);
  }

  #[Route('/post/{post}/edit', name: 'app_post_edit')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function editPost(
    Post $post,
    Request $request,
    ImageUploader $imageUploader,
    EntityManagerInterface $entityManager
  ): Response {
    $form = $this->createForm(PostType::class, $post, [
      'is_edit' => true // informacja do formularza, że jestesmy w trybie edycji
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $postImageFile = $form->get('postImage')->getData();

      if ($postImageFile) {
        $oldImage = $post->getImage();

        try {
          $newFileName = $imageUploader->upload($postImageFile, $this->getParameter('posts_images_directory'));
          $post->setImage($newFileName);
        } catch (FileException $e) {
          $this->addFlash('error', 'An error occurred while uploading the image. Please try again.');
          return $this->render('post/edit.html.twig', [
            'form' => $form,
            'post' => $post,
            'originalImageName' => $imageUploader->getOriginalImageName(
              $post->getImage()
            )
          ]);
        }

        if ($oldImage) {
          $imageUploader->remove($oldImage, $this->getParameter('posts_images_directory'));
        }
      }
      $entityManager->flush();

      $this->addFlash('success', 'Your post has been updated.');
      return $this->redirectToRoute('app_post_show', [
        'post' => $post->getId()
      ]);
    }

    return $this->render('post/edit.html.twig', [
      'form' => $form,
      'post' => $post,
      'originalImageName' => $imageUploader->getOriginalImageName(
        $post->getImage()
      )
    ]);
  }

  #[Route('/post/{post}/delete', name: 'app_post_delete')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function deletePost(
    Post $post,
    PostRepository $posts,
    ImageUploader $imageUploader,
    EntityManagerInterface $entityManager
  ): Response {
    /** @var User $user */
    $user = $this->getUser();

    $oldImage = $post->getImage();

    if ($oldImage) {
      $imageUploader->remove($oldImage, $this->getParameter('posts_images_directory'));
    }
    $entityManager->remove($post);
    $entityManager->flush();

    $this->addFlash('success', 'Your post has been deleted.');
    return $this->redirectToRoute('app_post_main', [
      'posts' => $posts->findAllByAuthors(
        $user->getFollows()
      )
    ]);
  }
}
