<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Vote;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\PostVote as PostVoteDto;

class PostVoteService
{
    /**
     * @var VoteRepository
     */
    private $voteRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(VoteRepository $voteRepository, EntityManagerInterface $em)
    {
        $this->voteRepository = $voteRepository;
        $this->em = $em;
    }

    public function store(PostVoteDto $postVote): void
    {
        /** @var Vote $vote */
        $vote = $this->voteRepository->findOneBy([
            'user' => $postVote->getAuthor(),
            'post' => $postVote->getPost()
        ]);

        // Update state
        if ($vote) {
            $vote->setVote($postVote->getVote());
        } else { // new vote
            $newVote = new Vote();
            $newVote->setUser($postVote->getAuthor());
            $newVote->setPost($postVote->getPost());
            $newVote->setVote($postVote->getVote());
            $this->em->persist($newVote);
        }

        $this->em->flush();
    }
}
