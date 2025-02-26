<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('frontend/post/index.html.twig', [
            'controller_name' => 'Frontend/PostController',
        ]);
    }
}
