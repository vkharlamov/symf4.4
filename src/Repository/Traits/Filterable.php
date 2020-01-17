<?php

namespace App\Repository\Traits;

use App\DTO\Filters\FilterDto;
use App\DTO\IRequestDto;
use App\Filters\Filter;
use Doctrine\ORM\QueryBuilder;

trait Filterable
{
    public function addFilters(
        QueryBuilder $qb,
        Filter $filter,
        IRequestDto $dto,
        string $alias): QueryBuilder
    {
        $filter->setDto($dto);
        $filter->setEnityAlias($alias);
        $filter->filter($qb);

        return $qb;
    }
}
