<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
  #[Route('/login', name: 'app_login')]
  public function index(AuthenticationUtils $utils): Response
  {
    /** @var User $user */

    if ($this->getUser()) {
      return $this->redirectToRoute('app_post_main');
    }

    $lastUsername = $utils->getLastUsername();
    $error = $utils->getLastAuthenticationError();

    return $this->render('login/index.html.twig', [
      'lastUsername' => $lastUsername,
      'error' => $error
    ]);
  }

  #[Route('/logout', name: 'app_logout')]
  public function logout(): void {}
}
