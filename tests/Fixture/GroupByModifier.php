<?php

namespace MF\QueryBuilderComposer\Tests\Fixture;

use Doctrine\ORM\QueryBuilder;
use MF\QueryBuilderComposer\Modifier;

class GroupByModifier implements Modifier
{
    /** @var string */
    private $groupBy;

    public function __construct(string $groupBy)
    {
        $this->groupBy = $groupBy;
    }

    public function __invoke(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->groupBy($this->groupBy);
    }
}
