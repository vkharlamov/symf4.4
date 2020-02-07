<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Vote;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostVoteFixture
 * @package App\DataFixtures
 */
class PostVoteFixture extends BaseFixture implements DependentFixtureInterface
{
    private $postRepository;
    private $userRepository;
    private $em;

    public function __construct(
        PostRepository $postRepository,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function loadData(ObjectManager $manager): void
    {
        $posts = $this->postRepository->getRandomPosts(10, Post::STATUS_PUBLISHED_KEY);
        $users = $this->userRepository->getRandomConfirmedUsers(10);

        foreach ($posts as $post) {
            foreach ($users as $user) {
                if ($post->getUser()->getId() === $user->getId()) {
                    continue;
                }
                $date = $this->faker->dateTimeThisMonth;
                $vote = new Vote();
                $vote->setPost($post);
                $vote->setUser($user);
                $vote->setVote(array_rand(Vote::VOTE, 1));
                $vote->setCreatedAt($date);
                $vote->setUpdatedAt($date);

                $this->em->persist($vote);
            }
        }
        $this->em->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            PostFixture::class
        ];
    }
}
