<?php

declare(strict_types=1);

namespace App\Filters;

use App\DTO\Filters\FilterDto;
use App\DTO\IRequestDto;
use Doctrine\ORM\QueryBuilder;

abstract class Filter
{
    /**
     * @var IRequestDto
     */
    protected $dto;

    /**
     * @var QueryBuilder
     */
    protected $builder;
    protected $alias;

    abstract protected function getActiveFilters(): array;

    public function filter(QueryBuilder $builder): QueryBuilder
    {
        $this->builder = $builder;

//        var_dump($this->dto->dateFrom);

        foreach ($this->getActiveFilters() as $filter) {
            $value = $this->dto->{$filter};
            if (!empty($value) && method_exists($this, $filter)) {
                $this->{$filter}($value);
            }
        }
//        dd($this->builder->getQuery());
        return $this->builder;
    }

    public function setDto(IRequestDto $dto): void
    {
        $this->dto = $dto;
    }

    public function setEnityAlias(string $alias): void
    {
        $this->alias = $alias;
    }
}
