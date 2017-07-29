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
     * @param QueryBuilder $queryBuilder
     * @param array $parts
     * @return QueryBuilder
     */
    public function compose(QueryBuilder $queryBuilder, array $parts): QueryBuilder
    {
        return compose($parts, $queryBuilder);
    }

    /**
     * Use for compose different part groups given in more arguments
     *
     * Difference between compose and merge compose:
     * - compose($queryBuilder, array_merge($baseParts, $otherParts));
     * - mergeCompose($queryBuilder, $baseParts, $otherParts);
     *
     * @param QueryBuilder $queryBuilder
     * @param array[] ...$partGroups
     * @return QueryBuilder
     */
    public function mergeCompose(QueryBuilder $queryBuilder, array ...$partGroups): QueryBuilder
    {
        return $this->compose($queryBuilder, mergePartGroups($partGroups));
    }
}
