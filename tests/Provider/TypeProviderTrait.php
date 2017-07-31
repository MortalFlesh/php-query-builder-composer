<?php

namespace MF\QueryBuilderComposer\Tests\Provider;

use Doctrine\ORM\QueryBuilder;

trait TypeProviderTrait
{
    public function isPartProvider()
    {
        return [
            'modifier - callable' => [
                function (QueryBuilder $queryBuilder) {
                    return $queryBuilder;
                },
                true,
            ],
            'rule - string[]' => [
                ['select', 's.id'],
                true,
            ],
            'rule - single string in string[]' => [
                ['select s.id'],
                true,
            ],
            'rule - single string' => [
                'select s.id',
                true,
            ],
            'rule - invalid - string[]' => [
                ['invalid', 's.id'],
                false,
            ],
            'rule - invalid - single string in string[]' => [
                ['invalid s.id'],
                false,
            ],
            'rule - invalid - single string' => [
                'invalid s.id',
                false,
            ],
        ];
    }

    public function isModifierProvider()
    {
        return [
            'modifier - callable' => [
                function (QueryBuilder $queryBuilder): QueryBuilder {
                    return $queryBuilder;
                },
                true,
            ],
        ];
    }

    public function isSingleStringPartProvider()
    {
        return [
            'rule - single string' => [
                'select s.id',
                true,
            ],
            'rule - invalid - single string' => [
                'invalid s.id',
                false,
            ],
        ];
    }

    public function isRuleProvider()
    {
        return [
            'rule - select - string[]' => [
                ['select', 's.id'],
                true,
            ],
            'rule - from - string[]' => [
                ['from', 'student', 's'],
                true,
            ],
            'rule - single string in string[]' => [
                ['select s.id'],
                true,
            ],
            'rule - invalid - string[]' => [
                ['invalid', 's.id'],
                false,
            ],
            'rule - invalid - single string in string[]' => [
                ['invalid s.id'],
                false,
            ],
        ];
    }

    public function isQueryBuilderMethodProvider()
    {
        return [
            'invalid' => ['invalid', false],
            'select' => ['select', true],
            'from' => ['from', true],
            'where' => ['where', true],
            'andWhere' => ['andWhere', true],
            'join' => ['join', true],
        ];
    }
}
