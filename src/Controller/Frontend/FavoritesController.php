<?php

namespace  App\Controller\Frontend;

use App\Entity\Favorites;
use App\Entity\Posts;
use App\Repository\FavoritesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class FavoritesController extends AbstractController
{
    #[Route('/posts/{id}/favorite', name: 'app_favorite_toggle', methods: ['POST'])]
    public function toggleFavorite(Posts $post ,FavoritesRepository $favoritesRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Not authenticated'], 403);
        }

        $existingFavorite = $favoritesRepository->findOneBy(['post' => $post, 'user' => $user]);

        if ($existingFavorite) {
            $entityManager->remove($existingFavorite);
            $favorited = false;
        } else {
            $favorite = new Favorites();
            $favorite->setPost($post);
            $favorite->setUser($user);
            $entityManager->persist($favorite);
            $favorited = true;
        }

        $entityManager->flush();

        return $this->json([
            'favorited' => $favorited
        ]);
    }

}
