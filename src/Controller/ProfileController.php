<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfileController extends AbstractController
{
  #[Route('/profile/{id}', name: 'app_profile')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function show(User $user): Response
  {
    return $this->render('profile/show.html.twig', [
      'user' => $user,
    ]);
  }

  #[Route('/profile/{id}/follows', name: 'app_profile_follows')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function follows(User $user): Response
  {
    return $this->render('profile/show.html.twig', [
      'user' => $user,
    ]);
  }

  #[Route('/profile/{id}/followers', name: 'app_profile_followers')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function followers(User $user): Response
  {
    return $this->render('profile/show.html.twig', [
      'user' => $user,
    ]);
  }
}
