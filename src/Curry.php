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
 *      string[]
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
     * @return callable (Part[], QueryBuilder) -> QueryBuilder
     */
    public static function compose(): callable
    {
        return curry_n(2, COMPOSE);
    }

    /**
     * @return callable (QueryBuilder, Part) -> QueryBuilder
     */
    public static function applyPart(): callable
    {
        return curry_n(2, APPLY_PART);
    }

    /**
     * @return callable (QueryBuilder -> QueryBuilderModifier) -> QueryBuilder
     */
    public static function applyModifier(): callable
    {
        return curry_n(2, APPLY_MODIFIER);
    }

    /**
     * @return callable (QueryBuilder -> Part) -> QueryBuilder
     */
    public static function applyRule(): callable
    {
        return curry_n(2, APPLY_RULE);
    }

    /**
     * @return callable (Append -> DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifier(): callable
    {
        return curry_n(4, MODIFIER);
    }

    /**
     * @return callable (DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifierAppend(): callable
    {
        return curry_n(3, MODIFIER_APPEND);
    }

    /**
     * @return callable (DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifierSet(): callable
    {
        return curry_n(3, MODIFIER_SET);
    }
}
