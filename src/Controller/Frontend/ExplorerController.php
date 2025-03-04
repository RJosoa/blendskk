<?php

namespace  App\Controller\Frontend;

use App\Repository\FavoritesRepository;
use App\Repository\LikesRepository;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExplorerController extends AbstractController{
    #[Route('/explorer', name: 'app_explorer')]
    public function index(PostsRepository $postsRepository, LikesRepository $likesRepository, FavoritesRepository $favoritesRepository): Response
    {
        $posts = $postsRepository->findAll();
        return $this->render('explorer/index.html.twig', [
            'controller_name' => 'Frontend/ExplorerController',
            'posts' => $posts,
            'likesRepository' => $likesRepository,
            'favoritesRepository' => $favoritesRepository
        ]);
    }
}
