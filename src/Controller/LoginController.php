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
    $currentUser = $this->getUser();
    if ($currentUser) {
      return $this->redirectToRoute('app_dashboard');
    }

    $lastUsername = $utils->getLastUsername();
    $error = $utils->getLastAuthenticationError();

    return $this->render('login/index.html.twig', [
      'lastUsername' => $lastUsername,
      'error' => $error
    ]);
  }
}
