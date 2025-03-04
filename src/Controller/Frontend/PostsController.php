<?php

namespace App\Controller\Frontend;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\FavoritesRepository;
use App\Repository\LikesRepository;
use App\Repository\PostsRepository;
use Cloudinary\Cloudinary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/posts')]
final class PostsController extends AbstractController{
    #[Route(name: 'app_posts_index', methods: ['GET'])]
    public function index(PostsRepository $postsRepository, LikesRepository $likesRepository, FavoritesRepository $favoritesRepository): Response
    {
        $posts = $postsRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
            'likesRepository' => $likesRepository,
            'favoritesRepository' => $favoritesRepository
        ]);
    }

    #[Route('/{id}/show', name: 'app_posts_show', methods: ['GET'])]
    public function show(Posts $post, LikesRepository $likesRepository, FavoritesRepository $favoritesRepository): Response
    {
        return $this->render('posts/show.html.twig', [
            'post' => $post,
            'likesRepository' => $likesRepository,
            'favoritesRepository' => $favoritesRepository
        ]);
    }

    #[Route('/new', name: 'app_posts_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Posts();
        $post->setUser($this->getUser());
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $cloudinary = new Cloudinary($_ENV['CLOUDINARY_URL']);

                $folder = 'user/' . $this->getUser()->getId() . '/post';
                $uploadResult = $cloudinary->uploadApi()->upload(
                    $imageFile->getPathname(),
                    ['folder' => $folder]
                );

                $secureUrl = $uploadResult['secure_url'];
                $post->setFeatureImage($secureUrl);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_explorer', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('posts/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_posts_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        if ($post->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only edit your own posts.');
        }
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_explorer', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('posts/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_posts_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        if ($post->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can only delete your own post.');
        }
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_explorer', [], Response::HTTP_SEE_OTHER);
    }
}
