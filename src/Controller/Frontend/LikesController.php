<?php

namespace App\Controller\Frontend;

use App\Entity\Likes;
use App\Entity\Posts;
use App\Repository\LikesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LikesController extends AbstractController
{
    #[Route('/post/{id}/like', name: 'app_like_toggle', methods: ['POST'])]
    public function toggleLike(
        Request $request,
        Posts $post,
        LikesRepository $likesRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Not authenticated'], 403);
        }

        // Vérifie si l'utilisateur a déjà liké ce post
        $existingLike = $likesRepository->findOneBy(['post' => $post, 'user' => $user]);

        if ($existingLike) {
            // L'utilisateur a déjà liké -> on retire le like
            $entityManager->remove($existingLike);
            $liked = false;
        } else {
            $like = new Likes();
            $like->setPost($post);
            $like->setUser($user);
            $entityManager->persist($like);
            $liked = true;
        }

        $entityManager->flush();

        // Recalcule le nombre de likes pour ce post
        $likeCount = $likesRepository->countLikesByPost($post);

        return new JsonResponse([
            'likeCount' => $likeCount,
            'liked' => $liked
        ]);
    }
}
