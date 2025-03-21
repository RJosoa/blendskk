<?php

namespace App\Controller\Frontend;

use App\Service\MongoDbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MongoDbService $mongoDbService): Response
    {
        $mongoDbService->insertVisit('home');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Frontend/HomeController',
        ]);
    }
}
