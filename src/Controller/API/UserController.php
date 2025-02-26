<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController{
    #[Route('/user', name: 'app_a_p_i_user')]
    public function index(): Response
    {
        return $this->render('api/user/index.html.twig', [
            'controller_name' => 'API/UserController',
        ]);
    }
}
