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
        return curry_n(2, compose);
    }

    /**
     * @return callable (QueryBuilder, Part) -> QueryBuilder
     */
    public static function applyPart(): callable
    {
        return curry_n(2, applyPart);
    }

    /**
     * @return callable (QueryBuilder -> QueryBuilderModifier) -> QueryBuilder
     */
    public static function applyModifier(): callable
    {
        return curry_n(2, applyModifier);
    }

    /**
     * @return callable (QueryBuilder -> Part) -> QueryBuilder
     */
    public static function applyRule(): callable
    {
        return curry_n(2, applyRule);
    }

    /**
     * @return callable (Append -> DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifier(): callable
    {
        return curry_n(4, modifier);
    }

    /**
     * @return callable (DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifierAppend(): callable
    {
        return curry_n(3, modifierAppend);
    }

    /**
     * @return callable (DqlQueryPart -> Expr -> QueryBuilder) -> QueryBuilder
     */
    public static function modifierSet(): callable
    {
        return curry_n(3, modifierSet);
    }
}
