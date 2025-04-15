<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\UserProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SettingsProfileController extends AbstractController
{
  #[Route('/settings/profile', name: 'app_settings_profile')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function profile(Request $request): Response
  {
    /** @var User $user */

    $user = $this->getUser();
    $userProfile = $user->getUserProfile() ?? new UserProfile();

    $form = $this->createForm(UserProfileType::class, $userProfile);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $userProfile = $form->getData();

      // save
      // flash
      // redirect
    }

    return $this->render('settings_profile/profile.html.twig', [
      'form' => $form
    ]);
  }
}
