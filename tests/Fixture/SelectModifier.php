<?php

namespace MF\QueryBuilderComposer\Tests\Fixture;

use Doctrine\ORM\QueryBuilder;
use MF\QueryBuilderComposer\Modifier;

class SelectModifier implements Modifier
{
    /** @var string */
    private $column;

    public function __construct(string $column)
    {
        $this->column = $column;
    }

    public function __invoke(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->select($this->column);
    }

    public static function addSelectId(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->select('s.id');
    }
}
