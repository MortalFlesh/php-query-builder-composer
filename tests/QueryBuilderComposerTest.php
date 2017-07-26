<?php

namespace MF\QueryBuilderComposer\Tests;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use MF\QueryBuilderComposer\Curry;
use MF\QueryBuilderComposer\QueryBuilderComposer;
use MF\QueryBuilderComposer\Tests\Fixture\GroupByModifier;
use Mockery as m;

class QueryBuilderComposerTest extends QueryBuilderComposerTestCase
{
    /** @var QueryBuilderComposer */
    private $composer;

    public function setUp()
    {
        $this->composer = new QueryBuilderComposer();
    }

    public function testShouldApplyRulesToQueryBuilder()
    {
        $approvedExpr = new Expr\Andx('b.approved = true');
        $approvedModifier = Curry::modifier()(true)('where')($approvedExpr);

        $queryBuilder = m::mock(QueryBuilder::class);
        $queryBuilder->shouldReceive('select')
            ->with('s.id', 's.name')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('addSelect')
            ->with('s.age')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('from')
            ->with('student', 's')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('andWhere')
            ->with('s.id > :id')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('add')
            ->with('where', $approvedExpr, true)
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('orderBy')
            ->with('s.name', 'asc')
            ->once()
            ->andReturn($queryBuilder);
        $queryBuilder->shouldReceive('groupBy')
            ->with('s.id')
            ->once()
            ->andReturn($queryBuilder);

        $expectedQueryBuilder = $queryBuilder;
        $rules = [
            ['select', 's.id', 's.name'],
            ['addSelect', 's.age'],
            ['from', 'student', 's'],
            $approvedModifier,
            [$this, 'applySortToQueryBuilder'],
            ['andWhere', 's.id > :id'],
            new GroupByModifier('s.id')
        ];

        $queryBuilder = $this->composer->compose($rules, $queryBuilder);

        $this->assertSame($expectedQueryBuilder, $queryBuilder);
    }

    public static function applySortToQueryBuilder(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->orderBy('s.name', 'asc');
    }
}
