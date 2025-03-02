<?php

namespace App\Controller\API;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/categories')]
class CategoriesApiController extends AbstractController
{
    #[Route('/', name: 'api_categories_list', methods: ['GET'])]
    public function list(CategoriesRepository $categoriesRepository, SerializerInterface $serializer): JsonResponse
    {
        $categories = $categoriesRepository->findAll();
         if(!$categories){
            return new JsonResponse(['message' => 'Categories not found']);
    }
    $jsonCategories = $serializer->serialize($categories, 'json', ['groups' => 'categories']);
    return new JsonResponse($jsonCategories, 200, [], true);
    }

    #[Route('/new', name: 'api_category_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->getContent();
        $category = $serializer->deserialize($data, Categories::class, 'json');
        $em->persist($category);
        $em->flush();
        return new JsonResponse(['message' => 'Category created successfully'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_category_edit', methods: ['PUT'])]
    public function edit(Request $request, CategoriesRepository $categoriesRepository, EntityManagerInterface $em, int $id): JsonResponse
    {
        $category = $categoriesRepository->find($id);
        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        empty($data['name']) ? true : $category->setName($data['name']);
        $em->persist($category);
        $em->flush();
        return new JsonResponse(['message' => 'Category updated successfully'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_category_delete', methods: ['DELETE'])]
    public function delete(CategoriesRepository $categoriesRepository, EntityManagerInterface $em, int $id): JsonResponse
    {
        $category = $categoriesRepository->find($id);
        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
        $em->remove($category);
        $em->flush();
        return new JsonResponse(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
}
