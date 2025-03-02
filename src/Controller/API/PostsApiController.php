<?php

namespace App\Controller\API;

use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/posts')]
class PostsApiController extends AbstractController
{
    #[Route('/', name: 'api_posts_list', methods: ['GET'])]
    public function list(PostsRepository $postsRepository, SerializerInterface $serializer): JsonResponse
    {
        $postsList = $postsRepository->findAll();

        if (!$postsList) {
            return new JsonResponse(['message' => 'PostsList not found']);
        }

        $jsonPosts = $serializer->serialize($postsList, 'json', ['groups' => 'posts']);

        return new JsonResponse($jsonPosts, 200, [], true);
    }

    #[Route('/{id}', name: 'api_posts_delete', methods: ['DELETE'])]
    public function delete(PostsRepository $postsRepository, EntityManagerInterface $em, int $id): JsonResponse
    {
        $post = $postsRepository->find($id);

        if (!$post) {
            return new JsonResponse(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($post);
        $em->flush();

        return new JsonResponse(['message' => 'Post deleted successfully'], Response::HTTP_OK);
    }
}
