<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;

class PostService
{
    protected $postRepository;

    /**
     * PostService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
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
            $counter +=  $vote->getVote();
        }

        return $counter;
    }
}