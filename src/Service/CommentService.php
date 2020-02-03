<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Comment;
use App\DTO\Comment as CommentDto;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CommentService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param CommentDto $dto
     *
     * @throws \Exception
     */
    public function store(CommentDto $dto): void
    {
        $comment = new Comment();
        $comment->setPost($dto->getPost());
        $comment->setUser($dto->getAuthor());
        $comment->setText($dto->getComment());
        $comment->setIsVisible(Comment::STATUS_VISIBLE);
        $comment->setCreatedAt(new \DateTime());
        $comment->setUpdatedAt(new \DateTime());

        $this->em->persist($comment);
        $this->em->flush();
    }
}
