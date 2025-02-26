<?php

namespace  App\Controller\Frontend;;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TipsController extends AbstractController{
    #[Route('/tips', name: 'app_tips')]
    public function index(): Response
    {
        return $this->render('frontend/tips/index.html.twig', [
            'controller_name' => 'Frontend/TipsController',
        ]);
    }
}
