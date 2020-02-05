<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Post;
use App\Entity\User;

class PostVote
{
    private $author;
    private $vote;
    private $post;

    /*public function set(Post $post, User $author, int $vote): void
    {
        $this->author = $author;
        $this->post = $post;
        $this->vote = $vote;
    }*/
    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @param mixed $vote
     */
    public function setVote($vote): void
    {
        $this->vote = intval($vote);
    }
    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }
}
