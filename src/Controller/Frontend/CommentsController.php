<?php

namespace App\Controller\Frontend;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CommentsController extends AbstractController{
    #[Route('/comments',name: 'app_comments_index', methods: ['GET'])]
    public function index(CommentsRepository $commentsRepository): Response
    {
        return $this->render('comments/index.html.twig', [
            'comments' => $commentsRepository->findAll(),
        ]);
    }

    #[Route('posts/{id}/comments/new', name: 'app_comments_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Posts $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comments();
        $comment->setUser($this->getUser());
        $comment->setPost($post);

        if ($request->isMethod('POST') && $content = $request->request->get('content')) {
            $comment->setContent($content);
            $entityManager->persist($comment);
            $entityManager->flush();


            if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
                $csrfToken = $this->container->get('security.csrf.token_manager')
                    ->getToken('delete'.$comment->getId())
                    ->getValue();

                return $this->json([
                    'success' => true,
                    'comment' => [
                        'id' => $comment->getId(),
                        'content' => $comment->getContent(),
                        'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                    ],
                    'editLink' => $this->generateUrl('app_comments_edit', ['id' => $comment->getId()]),
                    'deleteLink' => $this->generateUrl('app_comments_delete', ['id' => $comment->getId()]),
                    'csrfToken' => $csrfToken,
                ]);
            }

            // For non-AJAX requests, redirect as before
            return $this->redirectToRoute('app_posts_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        // Rest of your method remains unchanged
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'post' => $post,
        ]);
    }

    #[Route('comments/{id}', name: 'app_comments_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Comments $comment): Response
    {
        return $this->render('comments/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('comments/{id}/edit', name: 'app_comments_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Comments $comment, EntityManagerInterface $entityManager): Response
    {
        if ($comment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own comments.');
        }

        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('commnts/{id}/delete', name: 'app_comments_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Comments $comment, EntityManagerInterface $entityManager): Response
    {
        if ($comment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own comments.');
        }

        $post = $comment->getPost();

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

    return $this->redirectToRoute('app_posts_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
    }
}
