<?php

namespace App\Form\DataTransformer;

use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsToTagStringTransformer implements DataTransformerInterface
{
    private $postRepository;
    private $tagRepository;
    private $finderCallback;

    public function __construct(
        PostRepository $postRepository,
        TagRepository $tagRepository,
        callable $finderCallback
    )
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->finderCallback = $finderCallback;
    }

    /**
     * Transform Tag entity object to string - prepare to build the 'select2' input
     * @param mixed $value
     *
     * @return mixed|string
     *
     */
    public function transform($value)
    {
//        dd(
//            //$value instanceof PersistentCollection,
//            $value,
//            $value->toArray()
//        );

        if (null === $value) {
            return []; // or [] @TODO
        }

        if (!$value instanceof PersistentCollection) {
            throw new \LogicException('The PostFormTYPE can only be used with Post objects');
        }
        // Check for array of Tag Entity
        if ($value->isEmpty()) {
            return [];
        }

        foreach ($value->toArray() as $tag) {
            $data[] = $tag->getName();
        }
        //dd(implode(',', $data));
//        return $data ?: implode(',', $data);
        return $data ?: [];
    }

    /**
     * Transform array to a collection of entities
     *
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        /**
         * @var String $value
         */
        if (!$value) {
            return [];
        }

        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a String.');
        }

        return explode(',', $value);

        /*$callback = $this->finderCallback;
        $user = $callback($this->userRepository, $value);

        if (!$user) {
            throw new TransformationFailedException(sprintf('No tag found with tag name "%s"', $value));
        }
        */

        return $user;
    }
}