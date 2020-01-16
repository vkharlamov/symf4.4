<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\IRequestDto;
use App\DTO\PostFilterRequest;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostService
 * @package App\Service
 */
class PostService
{
    protected $postRepository;

    protected $em;

    private $paginator;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        PostRepository $postRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        $this->postRepository = $postRepository;
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @param Post $post
     *
     * @return int
     */
    public function countVotes(Post $post): int
    {
        $counter = 0;

        foreach ($post->getVotes() as $vote) {
            $counter += $vote->getVote();
        }

        return $counter;
    }

    /**
     * @param Post $post
     */
    public function userPublishArticle(Post $post): void
    {
        if ($post->getStatus() === Post::STATUS_DRAFT_KEY) {
            $post->setStatus(Post::STATUS_MODERATE_KEY);
            $this->em->persist($post);
            $this->em->flush();
        }
    }

    public function update(Post $post): void
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * @param IRequestDto $postFilterRequest
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function getFilteredPosts(IRequestDto $postFilterRequest, int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->getFilteredPostList($postFilterRequest),
            $page,
            Post::LIMIT_PER_PAGE
        );
    }
}