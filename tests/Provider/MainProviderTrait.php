<?php

namespace MF\QueryBuilderComposer\Tests\Provider;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use MF\QueryBuilderComposer\Curry;
use MF\QueryBuilderComposer\Tests\Fixture\SelectModifier;
use Mockery as m;

trait MainProviderTrait
{
    public function composeProvider()
    {
        return [
            'empty' => [[], null],
            'one rule' => [
                [
                    'select s.id s.name',
                ],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id', 's.name')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'one modifier' => [
                [
                    function (QueryBuilder $queryBuilder) {
                        return $queryBuilder->select('s.id', 's.name');
                    },
                ],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id', 's.name')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'one modifier and one rule' => [
                [
                    function (QueryBuilder $queryBuilder) {
                        return $queryBuilder->select('s.id', 's.name');
                    },
                    'from student s',
                ],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id', 's.name')
                        ->once()
                        ->andReturnSelf();
                    $queryBuilder->shouldReceive('from')
                        ->with('student', 's')
                        ->once()
                        ->andReturnSelf();
                },
            ],
        ];
    }

    public function applyPartProvider()
    {
        return [
            'callable' => [
                function (QueryBuilder $queryBuilder) {
                    return $queryBuilder->select('s.id');
                },
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'string[]' => [
                ['select', 's.id'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'single string in string[]' => [
                ['select s.id'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'single string' => [
                'select s.id',
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            // todo add group in parts
        ];
    }

    public function invalidPartProvider()
    {
        return [
            'empty' => [[null]],
            'number' => [[1]],
            'bool' => [[self::MODIFIER_APPEND]],
        ];
    }

    public function applyModifierProvider()
    {
        return [
            'function' => [
                function (QueryBuilder $queryBuilder) {
                    return $queryBuilder->select('s.id', 's.name');
                },
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id', 's.name')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'closure' => [
                (function (string $column) {
                    return function (QueryBuilder $queryBuilder) use ($column) {
                        return $queryBuilder->select($column);
                    };
                })('s.id'),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'Modifier' => [
                new SelectModifier('s.id'),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'static method' => [
                [SelectModifier::class, 'addSelectId'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'method' => [
                [$this, 'addSelectId'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'modifier' => [
                Curry::modifier()(self::MODIFIER_APPEND)('select')(new Expr\Select('s.id')),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('add')
                        ->with('select', m::mustBe(new Expr\Select('s.id')), self::MODIFIER_APPEND)
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'modifier-append' => [
                Curry::modifierAppend()('select')(new Expr\Select('s.id')),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('add')
                        ->with('select', m::mustBe(new Expr\Select('s.id')), self::MODIFIER_APPEND)
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'modifier-set' => [
                Curry::modifierSet()('select')(new Expr\Select('s.id')),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('add')
                        ->with('select', m::mustBe(new Expr\Select('s.id')), !self::MODIFIER_APPEND)
                        ->once()
                        ->andReturnSelf();
                },
            ],
        ];
    }

    public function addSelectId(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->select('s.id');
    }

    public function ruleProvider()
    {
        return [
            'string[]' => [
                ['select', 's.id'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('select')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'single string in string[]' => [
                ['addSelect s.id'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('addSelect')
                        ->with('s.id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'from' => [
                ['from table t'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('from')
                        ->with('table', 't')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'join' => [
                ['join t.join j'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('join')
                        ->with('t.join', 'j')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'join with' => [
                ['join', 't.join', 'j', Expr\Join::WITH, 'j.approve = true'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('join')
                        ->with('t.join', 'j', Expr\Join::WITH, 'j.approve = true')
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'set parameter' => [
                ['setParameter', 'id', 1],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('setParameter')
                        ->with('id', 1)
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'set parameters' => [
                ['setParameters', ['id' => 1]],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('setParameters')
                        ->with(['id' => 1])
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'where' => [
                ['where', 's.id = :id'],
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('where')
                        ->with('s.id = :id')
                        ->once()
                        ->andReturnSelf();
                },
            ],
        ];
    }

    public function sanitizeRuleProvider()
    {
        return [
            'string []' => [['select', 's.id'], ['select', 's.id']],
            'single string in string []' => [['select s.id'], ['select', 's.id']],
        ];
    }

    public function modifierProvider()
    {
        return [
            'append select' => [
                self::MODIFIER_APPEND,
                'select',
                new Expr\Select('s.id'),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('add')
                        ->with('select', m::mustBe(new Expr\Select('s.id')), self::MODIFIER_APPEND)
                        ->once()
                        ->andReturnSelf();
                },
            ],
            'set from' => [
                !self::MODIFIER_APPEND,
                'from',
                new Expr\From('table', 't'),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('add')
                        ->with('from', m::mustBe(new Expr\From('table', 't')), !self::MODIFIER_APPEND)
                        ->once()
                        ->andReturnSelf();
                },
            ],
        ];
    }

    public function appendModifierProvider()
    {
        return [
            'append select' => [
                'select',
                new Expr\Select('s.id'),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('add')
                        ->with('select', m::mustBe(new Expr\Select('s.id')), self::MODIFIER_APPEND)
                        ->once()
                        ->andReturnSelf();
                },
            ],
        ];
    }

    public function setModifierProvider()
    {
        return [
            'set from' => [
                'from',
                new Expr\From('table', 't'),
                function (m\MockInterface $queryBuilder) {
                    $queryBuilder->shouldReceive('add')
                        ->with('from', m::mustBe(new Expr\From('table', 't')), !self::MODIFIER_APPEND)
                        ->once()
                        ->andReturnSelf();
                },
            ],
        ];
    }

    public function mergeGroupPartsProvider()
    {
        return [
            'rules (select+from) + (join+where)' => [
                'groups' => [
                    [
                        ['select s j'],
                        ['from student s'],
                    ],
                    [
                        ['join s.join j'],
                        ['where s.approve = true'],
                    ],
                ],
                'expected' => [
                    ['select s j'],
                    ['from student s'],
                    ['join s.join j'],
                    ['where s.approve = true'],
                ],
            ],
            'rules + modifiers (select+from) + (join+where)' => [
                'groups' => [
                    [
                        new SelectModifier('s, j'),
                        Curry::modifierSet()('from')(new Expr\From('student', 's')),
                    ],
                    [
                        ['join s.join j'],
                        function (QueryBuilder $queryBuilder) {
                            return $queryBuilder->where('s.approve = true');
                        },
                    ],
                ],
                'expected' => [
                    new SelectModifier('s, j'),
                    Curry::modifierSet()('from')(new Expr\From('student', 's')),
                    ['join s.join j'],
                    function (QueryBuilder $queryBuilder) {
                        return $queryBuilder->where('s.approve = true');
                    },
                ],
            ],
        ];
    }
}
