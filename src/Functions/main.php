<?php

namespace MF\QueryBuilderComposer\Functions;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use function Functional\every;

const QBC_NAMESPACE = 'MF\\QueryBuilderComposer\\Functions\\';

const COMPOSE = QBC_NAMESPACE . 'compose';
const APPLY_PART = QBC_NAMESPACE . 'applyPart';
const APPLY_MODIFIER = QBC_NAMESPACE . 'applyModifier';
const APPLY_RULE = QBC_NAMESPACE . 'applyRule';
const MODIFIER = QBC_NAMESPACE . 'modifier';
const MODIFIER_APPEND = QBC_NAMESPACE . 'modifierAppend';
const MODIFIER_SET = QBC_NAMESPACE . 'modifierSet';

const APPEND = true;

function compose(array $parts, QueryBuilder $queryBuilder): QueryBuilder
{
    return array_reduce($parts, APPLY_PART, $queryBuilder);
}

function applyPart(QueryBuilder $queryBuilder, $part): QueryBuilder
{
    if (isModifier($part)) {
        return applyModifier($queryBuilder, $part);
    }

    if (isSingleStringPart($part)) {
        $part = singleStringPartToPart($part);
    } elseif (every($part, IS_PART)) {
        return compose($part, $queryBuilder);
    };

    if (isRule($part)) {
        return applyRule($queryBuilder, $part);
    }

    throw new \InvalidArgumentException(sprintf('Unrecognized part given. - %s.', var_export($part, true)));
}

function applyModifier(QueryBuilder $queryBuilder, callable $modifier): QueryBuilder
{
    return $modifier($queryBuilder);
}

function applyRule(QueryBuilder $queryBuilder, array $rule): QueryBuilder
{
    $rule = sanitizeRule($rule);
    $ruleMethod = array_shift($rule);

    if (method_exists($queryBuilder, $ruleMethod)) {
        return $queryBuilder->{$ruleMethod}(...$rule);
    }

    throw new \InvalidArgumentException(
        sprintf('Given rule "%s" is not recognized and cant be applied to QueryBuilder', $ruleMethod)
    );
}

/**
 * @param bool $append
 * @param string $dqlPartName
 * @param Expr\Base|Expr\From $dqlPart
 * @param QueryBuilder $queryBuilder
 * @return QueryBuilder
 */
function modifier(
    bool $append,
    string $dqlPartName,
    $dqlPart,
    QueryBuilder $queryBuilder
): QueryBuilder {
    return $queryBuilder->add($dqlPartName, $dqlPart, $append);
}

/**
 * @param string $dqlPartName
 * @param Expr\Base|Expr\From $dqlPart
 * @param QueryBuilder $queryBuilder
 * @return QueryBuilder
 */
function modifierAppend(string $dqlPartName, $dqlPart, QueryBuilder $queryBuilder): QueryBuilder
{
    return $queryBuilder->add($dqlPartName, $dqlPart, APPEND);
}

/**
 * @param string $dqlPartName
 * @param Expr\Base|Expr\From $dqlPart
 * @param QueryBuilder $queryBuilder
 * @return QueryBuilder
 */
function modifierSet(string $dqlPartName, $dqlPart, QueryBuilder $queryBuilder): QueryBuilder
{
    return $queryBuilder->add($dqlPartName, $dqlPart, !APPEND);
}

function mergePartGroups(array $partGroups): array
{
    return array_reduce($partGroups, function (array $parts, array $group) {
        return array_merge($parts, $group);
    }, []);
}
