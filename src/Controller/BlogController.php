<?php

namespace App\Controller;

use App\Dictionary\Constants;
use App\DTO\Comment as CommnetDto;
use App\Entity\Post;
use App\Form\CommentFormType;
use App\Form\VotePostFormType;
use App\Service\PostService;
use App\Service\CommentService;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends BaseController
{
    public function list(PostRepository $repository, PaginatorInterface $paginator, $page = Constants::DEFAULT_PAGE)
    {
        $pagination = $paginator->paginate(
            $repository->getPostListOrderedByNewest(),
            $page,
            Constants::POST_PER_PAGE
        );

        return $this->render('blog/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Post $post
     * @param PostService $postService
     * @param CommnetDto $commentDto
     * @param CommentService $commentService
     *
     * @return Response
     */
    public function show(
        Post $post,
        PostService $postService,
        CommnetDto $commentDto,
        CommentService $commentService
    ) {
        $commentForm = $this->createForm(CommentFormType::class, $commentDto);
        $commentForm->handleRequest($this->request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /** comment property has been set already */
            $commentDto->setAuthor($this->getUser());
            $commentDto->setPost($post);
            $commentService->store($commentDto);

            $this->addFlash('success', 'Success. Comment added.');

            return $this->redirectToRoute('blog_show', [
                'id' => $post->getId(),
            ]);
        }

        $userVote = null;
        if ($user = $this->getUser()) {
            $userVote = $postService->getPostUsersVote($post, $user);
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'userVote' => $userVote,
            'countVotes' => $postService->countVotes($post),
            'commentForm' => $commentForm->createView(),
            'voteForm' => $this->createForm(
                VotePostFormType::class,
                null,
                [
                    'action' => $this->generateUrl('user_vote_post', ['id' => $post->getId()], 0),
                ]
            )->createView(),
        ]);
    }
}
