<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('frontend/profile/index.html.twig', [
            'controller_name' => 'Frontend/ProfileController',
        ]);
    }
}
