<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Post;
use App\Entity\User;

class Comment
{
    private $post;
    private $comment;
    private $author;

    public function set(
        Post $post,
        string $comment,
        User $author
    ): void
    {
        $this->post = $post;
        $this->comment = $comment;
        $this->author = $author;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }


    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

}
