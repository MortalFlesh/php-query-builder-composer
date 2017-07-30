<?php

namespace MF\QueryBuilderComposer\Functions;

use function Functional\first;

const IS_ARRAY = QBC_NAMESPACE . 'isArray';

function isValidArray($array): bool
{
    return isArray($array) && !empty($array);
}

function isArray($element): bool
{
    return is_array($element);
}

function singleStringPartToPart(string $part): array
{
    return [$part];
}

function sanitizeRule(array $rule): array
{
    return isSingleStringRule($rule)
        ? singleStringRuleToRule(first($rule))
        : $rule;
}

function singleStringRuleToRule(string $rule): array
{
    return explode(' ', $rule);
}

function isValidString($string): bool
{
    return is_string($string) && !empty($string);
}
