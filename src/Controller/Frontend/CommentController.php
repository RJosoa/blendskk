<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentController extends AbstractController{
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('frontend/comment/index.html.twig', [
            'controller_name' => 'Frontend/CommentController',
        ]);
    }
}
