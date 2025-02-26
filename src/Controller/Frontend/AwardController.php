<?php

namespace  App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AwardController extends AbstractController{
    #[Route('/award', name: 'app_award')]
    public function index(): Response
    {
        return $this->render('award/index.html.twig', [
            'controller_name' => 'Frontend/AwardController',
        ]);
    }
}
