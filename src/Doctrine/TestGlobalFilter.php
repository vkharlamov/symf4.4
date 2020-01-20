<?php

namespace App\Doctrine;

use App\Entity\Post;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class TestGlobalFilter extends SQLFilter
{
    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        // or check interface
        // if (!$targetEntity->reflClass->implementsInterface('LocaleAware')) ..
        if ($targetEntity->getReflectionClass()->name !== Post::class) {
            return '';
        }

        return $targetTableAlias . '.status = ' . $this->getParameter('status');
    }
}