<?php

namespace  App\Controller\Frontend;

use App\Entity\Favorites;
use App\Form\FavoritesType;
use App\Repository\FavoritesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/favorites')]
final class FavoritesController extends AbstractController{
    #[Route(name: 'app_favorites_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(FavoritesRepository $favoritesRepository): Response
    {
        return $this->render('favorites/index.html.twig', [
            'favorites' => $favoritesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_favorites_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $favorite = new Favorites();
        $favorite->setUser($this->getUser());
        $form = $this->createForm(FavoritesType::class, $favorite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($favorite);
            $entityManager->flush();

            return $this->redirectToRoute('app_favorites_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('favorites/new.html.twig', [
            'favorite' => $favorite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favorites_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Favorites $favorite, EntityManagerInterface $entityManager): Response
    {
        if ($favorite->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own favorite.');
        }

        if ($this->isCsrfTokenValid('delete'.$favorite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_favorites_index', [], Response::HTTP_SEE_OTHER);
    }
}
