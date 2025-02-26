<?php

namespace  App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TipsController extends AbstractController{
    #[Route('/tips', name: 'app_a_p_i_tips')]
    public function index(): Response
    {
        return $this->render('api/tips/index.html.twig', [
            'controller_name' => 'API/TipsController',
        ]);
    }
}
