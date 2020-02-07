<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostFixture
 * @package App\DataFixtures
 */
class PostFixture extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @inheritDoc
     */
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'draft_posts', function () {
            return $this->getPostByStatus(Post::STATUS_DRAFT_KEY);
        });

        $this->createMany(30, 'published_posts', function () {
            return $this->getPostByStatus(Post::STATUS_PUBLISHED_KEY);
        });

        $manager->flush();
    }

    /**
     * @param int $status
     *
     * @return Post
     */
    public function getPostByStatus(int $status)
    {
        $post = new Post();
        $post->setTitle($this->faker->sentence);
        $post->setContent($this->faker->realText());
        $post->setCreatedAt($this->faker->dateTimeThisMonth);
        $post->setUpdatedAt($this->faker->dateTimeThisMonth);
        $post->setStatus($status);

        /** @var User $author */
        $author = $this->getRandomReference('confirmed_users');
        $post->setUser($author);

        $tags = $this->getRandomReferences('tags', mt_rand(0, 4));
        foreach ($tags as $tag) {
            $post->addTag($tag);
        }

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            UserFixture::class,
            TagFixture::class
        ];
    }
}
