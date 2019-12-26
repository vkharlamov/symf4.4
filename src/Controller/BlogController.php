<?php

namespace App\Controller;

use App\Entity\Post;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function list(PostRepository $repository, PaginatorInterface $paginator, $page)
    {
        $pagination = $paginator->paginate(
            $repository->findAll(),
            $page,
            1
        );

        return $this->render('blog/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function show($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (!$post) {
            // cause the 404 page not found to be displayed
            throw $this->createNotFoundException();
        }

        return $this->render('blog/show.html.twig', ['post' => $post]);
    }
}
