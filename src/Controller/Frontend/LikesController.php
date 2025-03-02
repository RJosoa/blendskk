<?php

namespace  App\Controller\Frontend;

use App\Entity\Likes;
use App\Form\LikesType;
use App\Repository\LikesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/likes')]
final class LikesController extends AbstractController{
    #[Route(name: 'app_likes_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(LikesRepository $likesRepository): Response
    {

        return $this->render('likes/index.html.twig', [
            'likes' => $likesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_likes_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $like = new Likes();
        $like->setUser($this->getUser());
        $form = $this->createForm(LikesType::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($like);
            $entityManager->flush();

            return $this->redirectToRoute('app_likes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('likes/new.html.twig', [
            'like' => $like,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_likes_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Likes $like, EntityManagerInterface $entityManager): Response
    {
        if ($like->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own like.');
        }
        if ($this->isCsrfTokenValid('delete'.$like->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($like);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_likes_index', [], Response::HTTP_SEE_OTHER);
    }
}
