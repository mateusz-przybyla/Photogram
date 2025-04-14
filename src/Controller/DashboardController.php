<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DashboardController extends AbstractController
{
  #[Route('/dashboard', name: 'app_dashboard')]
  #[IsGranted('IS_AUTHENTICATED_FULLY')]
  public function index(): Response
  {
    return $this->render('dashboard/index.html.twig', [
      'controller_name' => 'PostController',
    ]);
  }
}
