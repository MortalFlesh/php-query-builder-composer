<?php

namespace MF\QueryBuilderComposer;

use function Functional\curry_n;

/**
 * Types:
 *
 *  QueryBuilderModifier =
 *      (QueryBuilder -> QueryBuilder)
 *
 *  Rule =
 *      | string[]
 *      | string
 *
 *  Part =
 *      | QueryBuilderModifier
 *      | Rule
 *
 * @see \Doctrine\ORM\QueryBuilder::add()
 *  Append =
 *      bool
 *
 *  DqlQueryPart =
 *      string
 *
 *  Expr =
 *      Doctrine\ORM\Query\Expr
 *
 */
class Curry
{
    /**
     * see Types above
     * @return callable (Part[], QueryBuilder) -> QueryBuilder
     */
    public static function compose(): callable
    {
        return curry_n(2, COMPOSE);
    }

    /**
     * see Types above
     * @return callable (QueryBuilder, Part) -> QueryBuilder
     */
    public static function applyPart(): callable
    {
        return curry_n(2, APPLY_PART);
    }

    /**
     * see Types above
     * @return callable (QueryBuilder -> QueryBuilderModifier) -> QueryBuilder
     */
    public static function applyModifier(): callable
    {
        return curry_n(2, APPLY_MODIFIER);
    }

    /**
     * see Types above
     * @return callable (QueryBuilder -> Part) -> QueryBuilder
     */
    public static function applyRule(): callable
    {
        return curry_n(2, APPLY_RULE);
    }

    /**
     * see Types above
     * @return callable (Append -> DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifier(): callable
    {
        return curry_n(4, MODIFIER);
    }

    /**
     * see Types above
     * @return callable (DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifierAppend(): callable
    {
        return curry_n(3, MODIFIER_APPEND);
    }

    /**
     * see Types above
     * @return callable (DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifierSet(): callable
    {
        return curry_n(3, MODIFIER_SET);
    }
}
