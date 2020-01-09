<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\PostService;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    public function list(PostRepository $repository, PaginatorInterface $paginator, $page)
    {
        $pagination = $paginator->paginate(
            $repository->getPostListOrderedByNewest(),
            $page,
            Post::LIMIT_PER_PAGE
        );

        return $this->render('blog/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Post $post by id from request
     * @param PostService $postService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Post $post, PostService $postService)
    {
        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'countVotes' => $postService->countVotes($post),
        ]);
    }
}
