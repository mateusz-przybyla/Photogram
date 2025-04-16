<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Form\ProfileImageType;
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
    SluggerInterface $slugger,
    EntityManagerInterface $entityManager
  ): Response {
    $form = $this->createForm(ProfileImageType::class);

    /** @var User $user */
    $user = $this->getUser();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $profileImageFile = $form->get('profileImage')->getData();

      if ($profileImageFile) {
        $originalFilename = pathinfo(
          $profileImageFile->getClientOriginalName(),
          PATHINFO_FILENAME
        );
        $safeFilename = $slugger->slug($originalFilename);
        $newFileName = $safeFilename . '-' . uniqid() . '.' . $profileImageFile->guessExtension();

        try {
          $profileImageFile->move(
            $this->getParameter('profiles_directory'),
            $newFileName
          );
        } catch (FileException $e) {
          $error = 'There was an error uploading your file. Please try again.';
        }

        $userProfile = $user->getUserProfile();

        if (!$userProfile) {
          $userProfile = new UserProfile();
          $user->setUserProfile($userProfile);
          $entityManager->persist($user);
        }

        $userProfile->setImage($newFileName);
        $entityManager->flush();

        $this->addFlash('success', 'Your profile image was updated.');

        return $this->redirectToRoute('app_settings_profile_image');
      }
    }

    return $this->render('settings_profile/profile_image.html.twig', [
      'form' => $form,
      'error' => $error ?? null
    ]);
  }
}
