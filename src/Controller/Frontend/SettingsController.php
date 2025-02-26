<?php

namespace  App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SettingsController extends AbstractController{
    #[Route('/settings', name: 'app_settings')]
    public function index(): Response
    {
        return $this->render('frontend/settings/index.html.twig', [
            'controller_name' => 'Frontend/SettingsController',
        ]);
    }
}
