<?php

namespace MF\QueryBuilderComposer;

use Doctrine\ORM\QueryBuilder;

class QueryBuilderComposer
{
    /**
     * Parts is array of:
     * - modifiers
     * - rules
     *
     * Modifier:
     * | modifier is ANY callable by this pattern: (QueryBuilder -> QueryBuilder)
     * | @see \MF\QueryBuilderComposer\Modifier
     *
     * example of modifiers:
     * - (anonymus function): [ function(QueryBuilder $qb) { return $qb->select('...'); }, ... ]
     * - (static function)  : [ [$this, 'modifyQueryBuilder'], ... ]
     * - (closure)          : [ $addSelectModifier, ... ]
     * - (Modifier)         : [ new Modifier('...'), ... ]
     * - ...
     *
     * Rule:
     * | rule is array of strings which represents any QueryBuilder method call
     *
     * example of rules:
     * - (QueryBuilder method call) : (rule representation)
     * - $qb->select('t.column')    : ['select', 't.column']
     * - $qb->join('t.joined', 'j') : ['join', 't.joined', 'j']
     * - ...
     *
     * @param array $parts
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     */
    public function compose(array $parts, QueryBuilder $queryBuilder): QueryBuilder
    {
        return compose($parts, $queryBuilder);
    }
}
