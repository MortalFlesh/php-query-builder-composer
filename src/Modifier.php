<?php

namespace MF\QueryBuilderComposer;

use Doctrine\ORM\QueryBuilder;

interface Modifier
{
    public function __invoke(QueryBuilder $queryBuilder): QueryBuilder;
}
