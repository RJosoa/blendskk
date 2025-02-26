<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentController extends AbstractController{
    #[Route('/comment', name: 'app_a_p_i_comment')]
    public function index(): Response
    {
        return $this->render('api/comment/index.html.twig', [
            'controller_name' => 'API/CommentController',
        ]);
    }
}
