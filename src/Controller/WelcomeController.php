<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WelcomeController extends AbstractController
{
  #[Route('/', name: 'app_index')]
  public function index(): Response
  {
    return $this->render('welcome/index.html.twig');
  }
}
