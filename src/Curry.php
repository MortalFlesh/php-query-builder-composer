<?php

namespace MF\QueryBuilderComposer;

use const MF\QueryBuilderComposer\Functions\APPLY_MODIFIER;
use const MF\QueryBuilderComposer\Functions\APPLY_PART;
use const MF\QueryBuilderComposer\Functions\APPLY_RULE;
use const MF\QueryBuilderComposer\Functions\COMPOSE;
use const MF\QueryBuilderComposer\Functions\MODIFIER;
use const MF\QueryBuilderComposer\Functions\MODIFIER_APPEND;
use const MF\QueryBuilderComposer\Functions\MODIFIER_SET;
use function Functional\curry_n;

/**
 * Types:
 * @see Functions/type.php
 *
 *  Modifier =
 *      (QueryBuilder -> QueryBuilder)
 *
 *  Rule =
 *      string[]
 *
 *  Part =
 *      | string<Rule>
 *      | Modifier
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
     * @return callable (QueryBuilder -> Modifier) -> QueryBuilder
     */
    public static function applyModifier(): callable
    {
        return curry_n(2, APPLY_MODIFIER);
    }

    /**
     * see Types above
     * @return callable (QueryBuilder -> Rule) -> QueryBuilder
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
