<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController{
    #[Route('/category', name: 'app_a_p_i_category')]
    public function index(): Response
    {
        return $this->render('api/category/index.html.twig', [
            'controller_name' => 'API/CategoryController',
        ]);
    }
}
