<?php

namespace  App\Controller\Frontend;

use App\Repository\FavoritesRepository;
use App\Repository\LikesRepository;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExplorerController extends AbstractController{
    #[Route('/explorer', name: 'app_explorer')]
    public function index(
        PostsRepository $postsRepository,
        LikesRepository $likesRepository,
        FavoritesRepository $favoritesRepository,
        Request $request
    ): Response {
        $page = $request->query->getInt('page', 1);

        $paginatedPosts = $postsRepository->findPaginated($page, 12);

        return $this->render('explorer/index.html.twig', [
            'controller_name' => 'Frontend/ExplorerController',
            'posts' => $paginatedPosts['data'],
            'pagination' => [
                'currentPage' => $paginatedPosts['currentPage'],
                'totalPages' => $paginatedPosts['pageCount'],
                'totalItems' => $paginatedPosts['totalItems'],
                'limit' => $paginatedPosts['limit']
            ],
            'likesRepository' => $likesRepository,
            'favoritesRepository' => $favoritesRepository
        ]);
    }

}
