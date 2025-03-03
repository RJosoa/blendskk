<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
final class ProfileController extends AbstractController{
    #[Route('/{id}', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(User $user): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Frontend/ProfileController',
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_profile_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ): Response {
        // Check if trying to delete someone else's account
        if ($user !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own account');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $session = $request->getSession();

            $entityManager->remove($user);
            $entityManager->flush();

            $tokenStorage->setToken(null);

            $session->invalidate();

        }

        return $this->redirectToRoute('app_explorer', [], Response::HTTP_SEE_OTHER);
    }
}
