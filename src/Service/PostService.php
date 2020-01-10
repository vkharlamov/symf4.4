<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class PostService
 * @package App\Service
 */
class PostService
{

    protected $postRepository;

    protected $em;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository, EntityManagerInterface $em)
    {
        $this->postRepository = $postRepository;
        $this->em = $em;
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
}