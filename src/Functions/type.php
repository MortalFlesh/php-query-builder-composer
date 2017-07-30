<?php

namespace MF\QueryBuilderComposer\Functions;

use Doctrine\ORM\QueryBuilder;
use function Functional\first;

const IS_PART = QBC_NAMESPACE . 'isPart';
const IS_MODIFIER = QBC_NAMESPACE . 'isModifier';
const IS_SINGLE_STRING_RULE = QBC_NAMESPACE . 'isSingleStringRule';
const IS_RULE = QBC_NAMESPACE . 'isRule';

function isPart($part): bool
{
    return isModifier($part) || isRule($part) || isSingleStringPart($part);
}

function isModifier($rule): bool
{
    return is_callable($rule);
}

function isSingleStringPart($part): bool
{
    return isValidString($part) && isRule(singleStringPartToPart($part));
}

function isSingleStringRule(array $rule): bool
{
    return count($rule) === 1 && isValidString(first($rule));
}

function isRule($rule): bool
{
    return isValidArray($rule) && isValidString($method = first(sanitizeRule($rule))) && isQueryBuilderMethod($method);
}

function isQueryBuilderMethod(string $method): bool
{
    return method_exists(QueryBuilder::class, $method);
}
