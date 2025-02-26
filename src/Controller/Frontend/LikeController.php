<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LikeController extends AbstractController{
    #[Route('/like', name: 'app_like')]
    public function index(): Response
    {
        return $this->render('frontend/like/index.html.twig', [
            'controller_name' => 'Frontend/LikeController',
        ]);
    }
}
