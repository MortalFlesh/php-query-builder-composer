<?php

namespace MF\QueryBuilderComposer;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

const QBC_NAMESPACE = 'MF\\QueryBuilderComposer\\';

const compose = QBC_NAMESPACE . 'compose';
const applyPart = QBC_NAMESPACE . 'applyPart';
const applyModifier = QBC_NAMESPACE . 'applyModifier';
const applyRule = QBC_NAMESPACE . 'applyRule';
const modifier = QBC_NAMESPACE . 'modifier';
const modifierAppend = QBC_NAMESPACE . 'modifierAppend';
const modifierSet = QBC_NAMESPACE . 'modifierSet';

const APPEND = true;

function compose(array $parts, QueryBuilder $queryBuilder): QueryBuilder
{
    return array_reduce($parts, applyPart, $queryBuilder);
}

function applyPart(QueryBuilder $queryBuilder, $part): QueryBuilder
{
    if (is_callable($part)) {
        return applyModifier($queryBuilder, $part);
    }

    if (is_array($part)) {
        return applyRule($queryBuilder, $part);
    }

    throw new \InvalidArgumentException(sprintf('Unrecognized part given. - %s.', $part));
}

function applyModifier(QueryBuilder $queryBuilder, callable $modifier): QueryBuilder
{
    return $modifier($queryBuilder);
}

function applyRule(QueryBuilder $queryBuilder, array $rule): QueryBuilder
{
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
