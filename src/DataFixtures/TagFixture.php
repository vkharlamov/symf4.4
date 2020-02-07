<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagFixture
 * @package App\DataFixtures
 */
class TagFixture extends BaseFixture
{
    /**
     * @inheritDoc
     */
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'tags', function () {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->word);
            $tag->setCreatedAt($this->faker->dateTimeThisMonth);

            return $tag;
        });

        $manager->flush();
    }
}
