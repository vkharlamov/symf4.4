<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CommentFixture
 * @package App\DataFixtures
 */
class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(200, 'comments', function () {
            $comment = new Comment();
            $date = $this->faker->dateTimeThisMonth;
            $comment->setText($this->faker->realText());
            $comment->setIsVisible(mt_rand(0, 1));
            $comment->setCreatedAt($date);
            $comment->setUpdatedAt($date);

            /** @var User $author */
            $author = $this->getRandomReference('confirmed_users');
            $comment->setUser($author);

            /** @var Post $post */
            $post = $this->getRandomReference('published_posts');
            $comment->setPost($post);

            return $comment;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            PostFixture::class
        ];
    }
}
