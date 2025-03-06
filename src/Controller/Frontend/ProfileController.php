<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\FavoritesRepository;
use App\Repository\LikesRepository;
use App\Repository\PostsRepository;
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
    #[Route('/{id}/{tab}', name: 'app_profile_with_tab', requirements: ['tab' => 'created|liked|favorites'])]
    #[IsGranted('ROLE_USER')]
    public function index(
        User $user,
        PostsRepository $postsRepository,
        LikesRepository $likesRepository,
        FavoritesRepository $favoritesRepository,
        ?string $tab = 'created'
    ): Response {
        $posts = match($tab) {
            'created' => $postsRepository->findUserCreatedPosts($user->getId()),
            'liked' => $postsRepository->findUserLikedPosts($user->getId()),
            'favorites' => $postsRepository->findUserFavoritePosts($user->getId()),
            default => $postsRepository->findUserCreatedPosts($user->getId()),
        };

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Frontend/ProfileController',
            'user' => $user,
            'posts' => $posts,
            'activeTab' => $tab,
            'likesRepository' => $likesRepository,
            'favoritesRepository' => $favoritesRepository
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
