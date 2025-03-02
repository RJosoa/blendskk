<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
class UserApiController extends AbstractController
{
    #[Route('/', name: 'api_users_list', methods: ['GET'])]
    public function list(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $usersList = $userRepository->findAll();

        if (!$usersList) {
            return new JsonResponse(['message' => 'UsersList not found']);
        }

        $jsonUsers = $serializer->serialize($usersList, 'json', ['groups' => 'user']);

        return new JsonResponse($jsonUsers, 200, [], true);
    }


    #[Route('/new', name: 'api_users_new', methods: ['POST'])]
    public function new(Request $request, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $roles = $data['roles'];
        $agree_terms = $data['agree_terms'];

        $user = $userRepository->findOneBy(['username' => $username]);
        if ($user) {
            return new JsonResponse(['message' => 'User already exists!'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRoles($roles);
        $user->setAgreeTerms($agree_terms);
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_users_show', methods: ['GET'])]
    public function show(UserRepository $userRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user']);

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/edit', name: 'api_users_edit', methods: ['PUT'])]
    public function edit(Request $request, UserRepository $userRepository, EntityManagerInterface $em, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['roles']) && is_array($data['roles'])) {
            $user->setRoles($data['roles']);
            $em->flush();
            return new JsonResponse(['message' => 'User roles updated successfully'], Response::HTTP_OK);
        }

        return new JsonResponse(['message' => 'No roles provided or invalid format'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(UserRepository $userRepository, EntityManagerInterface $em, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }
}
