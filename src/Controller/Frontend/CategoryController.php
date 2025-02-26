<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('frontend/category/index.html.twig', [
            'controller_name' => 'Frontend/CategoryController',
        ]);
    }
}
