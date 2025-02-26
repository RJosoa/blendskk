<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController{
    #[Route('/post', name: 'app_a_p_i_post')]
    public function index(): Response
    {
        return $this->render('api/post/index.html.twig', [
            'controller_name' => 'API/PostController',
        ]);
    }
}
