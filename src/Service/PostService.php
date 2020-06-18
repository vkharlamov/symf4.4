<?php

declare(strict_types=1);

namespace App\Service;

use App\Dictionary\Constants;
use App\DTO\IRequestDto;
use App\DTO\PostFilterRequest;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

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
     * @var VoteRepository
     */
    private $voteRepository;
    /**
     * @var Security
     */
    private $security;

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
        PaginatorInterface $paginator,
        VoteRepository $voteRepository,
        Security $security
    ) {
        $this->postRepository = $postRepository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->voteRepository = $voteRepository;
        $this->security = $security;
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

    /**
     * @param Post $post
     */
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
    public function getFilteredPosts(IRequestDto $postFilterRequest, int $page = Constants::DEFAULT_PAGE): PaginationInterface
    {
        $query = $this->postRepository->getFilteredPostList($postFilterRequest);

        return $this->paginator->paginate(
            $query,
            $page,
            Constants::POST_PER_PAGE
        );
    }

    /**
     * @param Post $post
     * @param User $user
     *
     * @return int|null
     */
    public function getPostUsersVote(Post $post, User $user): ?int
    {
        $postVoteOfUser = $this->voteRepository
            ->findOneBy([
                'user' => $user,
                'post' => $post
            ]);

        return $postVoteOfUser
            ? $postVoteOfUser->getVote()
            : null;
    }

    /**
     * Create post. Empty tags allowed
     *
     * @param Post $post
     */
    public function create(Post $post): void
    {
        $post->setUser($this->security->getUser());

        $this->em->persist($post);
        $this->em->flush();
    }
}