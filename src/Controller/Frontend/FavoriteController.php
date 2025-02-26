<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoriteController extends AbstractController{
    #[Route('/favorite', name: 'app_favorite')]
    public function index(): Response
    {
        return $this->render('frontend/favorite/index.html.twig', [
            'controller_name' => 'Frontend/FavoriteController',
        ]);
    }
}
