<?php

namespace App\Controller\API;

use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/comments')]
class CommentsApiController extends AbstractController
{
    #[Route('/', name: 'api_comments_list', methods: ['GET'])]
    public function list(CommentsRepository $commentsRepository, SerializerInterface $serializer): JsonResponse
    {
        $commentsList = $commentsRepository->findAll();

        if (!$commentsList) {
            return new JsonResponse(['message' => 'CommentsList not found']);
        }

        $jsonComments = $serializer->serialize($commentsList, 'json', ['groups' => 'comments']);

        return new JsonResponse($jsonComments, 200, [], true);
    }

    #[Route('/{id}', name: 'api_comments_delete', methods: ['DELETE'])]
    public function delete(CommentsRepository $commentsRepository, EntityManagerInterface $em, int $id): JsonResponse
    {
        $comment = $commentsRepository->find($id);

        if (!$comment) {
            return new JsonResponse(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($comment);
        $em->flush();

        return new JsonResponse(['message' => 'Comment deleted successfully'], Response::HTTP_OK);
    }
}
