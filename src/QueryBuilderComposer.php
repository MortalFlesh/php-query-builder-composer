<?php

namespace MF\QueryBuilderComposer;

use Doctrine\ORM\QueryBuilder;

class QueryBuilderComposer
{
    /**
     * Parts are array of:
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
     * Rule (represents any QueryBuilder method call)
     * | rule is string[], array of single string or single string
     *
     * example of rules:
     *   (QueryBuilder method call) : (rule representation)
     * - $qb->select('t.column')    : ['select', 't.column']
     * - $qb->join('t.joined', 'j') : ['join', 't.joined', 'j']
     * - $qb->from('table', 't')    : ['from', 'table', 't']
     * - $qb->from('table', 't')    : ['from table t']
     * - $qb->from('table', 't')    : 'from table t'
     * - ...
     *
     * @param QueryBuilder $queryBuilder
     * @param array $parts
     * @return QueryBuilder
     */
    public static function compose(QueryBuilder $queryBuilder, array $parts): QueryBuilder
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
    public static function mergeCompose(QueryBuilder $queryBuilder, array ...$partGroups): QueryBuilder
    {
        return self::compose($queryBuilder, mergePartGroups($partGroups));
    }
}
