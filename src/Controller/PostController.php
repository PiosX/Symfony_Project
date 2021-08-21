<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;

#[Route('/post', name: 'post.')]

class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request)
    {
        //create a new post with title
        $post = new Post();

        $post->setTitle('This is going to be a title');

        //entity manager

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        //return a response 
        return new Response(content:'Post was created');

    }

    #[Route('/show/{id}', name: 'show')]
    public function show(Post $post)
    {
        //create the show view
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function remove(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();

        return $this->redirect($this->generateUrl(route:'post.index'));
    }
}
