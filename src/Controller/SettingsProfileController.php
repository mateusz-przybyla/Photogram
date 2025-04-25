<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Form\ProfileImageType;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class SettingsProfileController extends AbstractController
{
  #[Route('/settings/profile', name: 'app_settings_profile')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function profile(Request $request, EntityManagerInterface $entityManager): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $userProfile = $user->getUserProfile();

    if (!$userProfile) {
      $userProfile = new UserProfile();
      $user->setUserProfile($userProfile);
      $entityManager->persist($user);
    }

    $form = $this->createForm(UserProfileType::class, $userProfile);
    //dump($userProfile); // old data
    $form->handleRequest($request);
    //dump($userProfile); // new data from the form

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash(
        'success',
        'Your user profile settings were saved.'
      );

      return $this->redirectToRoute('app_settings_profile');
    }

    return $this->render('settings_profile/profile.html.twig', [
      'form' => $form
    ]);
  }

  #[Route('/settings/profile-image', name: 'app_settings_profile_image')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function profileImage(
    Request $request,
    ImageUploader $imageUploader,
    EntityManagerInterface $entityManager
  ): Response {
    $form = $this->createForm(ProfileImageType::class);

    /** @var User $user */
    $user = $this->getUser();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $profileImageFile = $form->get('profileImage')->getData();

      if ($profileImageFile) {
        $userProfile = $user->getUserProfile();

        if (!$userProfile) {
          $userProfile = new UserProfile();
          $user->setUserProfile($userProfile);
          $entityManager->persist($user);
        }

        try {
          $newFileName = $imageUploader->upload($profileImageFile, $this->getParameter('profiles_directory'));
          $userProfile->setImage($newFileName);
        } catch (FileException $e) {
          $this->addFlash('error', 'An error occurred while uploading the image. Please try again.');
          return $this->render('settings_profile/profile_image.html.twig', [
            'form' => $form
          ]);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Your profile image was updated.');
        return $this->redirectToRoute('app_settings_profile_image');
      }
    }

    return $this->render('settings_profile/profile_image.html.twig', [
      'form' => $form
    ]);
  }
}
