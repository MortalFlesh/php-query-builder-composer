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

    /** @var QueryBuilder|m\MockInterface */
    private $queryBuilder;

    public function setUp()
    {
        $this->composer = new QueryBuilderComposer();

        $this->queryBuilder = m::mock(QueryBuilder::class);
    }

    public function testShouldComposeAndApplyRulesToQueryBuilder()
    {
        $approvedExpr = new Expr\Andx('b.approved = true');
        $approvedModifier = Curry::modifier()(true)('where')($approvedExpr);

        $parts = [
            ['select', 's.id', 's.name'],
            ['addSelect', 's.age'],
            ['from', 'student', 's'],
            $approvedModifier,
            [$this, 'applySortToQueryBuilder'],
            ['andWhere', 's.id > :id'],
            new GroupByModifier('s.id'),
        ];

        $this->queryBuilder->shouldReceive('select')
            ->with('s.id', 's.name')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('addSelect')
            ->with('s.age')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('from')
            ->with('student', 's')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('andWhere')
            ->with('s.id > :id')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('add')
            ->with('where', $approvedExpr, true)
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('orderBy')
            ->with('s.name', 'asc')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('groupBy')
            ->with('s.id')
            ->once()
            ->andReturn($this->queryBuilder);

        $queryBuilder = $this->composer->compose($this->queryBuilder, $parts);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    public static function applySortToQueryBuilder(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->orderBy('s.name', 'asc');
    }

    public function testShouldComposeMergedPartsAndApplyRulesToQueryBuilder()
    {
        $approvedExpr = new Expr\Andx('b.approved = true');
        $approvedModifier = Curry::modifier()(true)('where')($approvedExpr);

        $approvedMature = [
            $approvedModifier,
            ['andWhere', 's.age >= 18'],
        ];

        $customParts = [
            ['select', 's.id, s.name, s.age'],
            ['from', 'student', 's'],
        ];

        $this->queryBuilder->shouldReceive('select')
            ->with('s.id, s.name, s.age')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('from')
            ->with('student', 's')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('add')
            ->with('where', $approvedExpr, true)
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('andWhere')
            ->with('s.age >= 18')
            ->once()
            ->andReturn($this->queryBuilder);

        $queryBuilder = $this->composer->compose($this->queryBuilder, array_merge($customParts, $approvedMature));

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    public function testShouldMergeComposePartsAndApplyRulesToQueryBuilder()
    {
        $approvedExpr = new Expr\Andx('b.approved = true');
        $approvedModifier = Curry::modifier()(true)('where')($approvedExpr);

        $approvedMature = [
            $approvedModifier,
            ['andWhere', 's.age >= 18'],
        ];

        $customParts = [
            ['select', 's.id, s.name, s.age'],
            ['from', 'student', 's'],
        ];

        $this->queryBuilder->shouldReceive('select')
            ->with('s.id, s.name, s.age')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('from')
            ->with('student', 's')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('add')
            ->with('where', $approvedExpr, true)
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('andWhere')
            ->with('s.age >= 18')
            ->once()
            ->andReturn($this->queryBuilder);

        $queryBuilder = $this->composer->mergeCompose($this->queryBuilder, $customParts, $approvedMature);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    public function testShouldComposeRulesAndApplyRulesToQueryBuilder()
    {
        $parts = [
            'select s.id s.name s.age',
            'from student s',
        ];

        $this->queryBuilder->shouldReceive('select')
            ->with('s.id', 's.name', 's.age')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('from')
            ->with('student', 's')
            ->once()
            ->andReturn($this->queryBuilder);

        $queryBuilder = $this->composer->compose($this->queryBuilder, $parts);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    public function testShouldComposeRulesBySingleStringAndApplyRulesToQueryBuilder()
    {
        $parts = [
            'select s.id s.name s.age',
            'from student s',
        ];

        $this->queryBuilder->shouldReceive('select')
            ->with('s.id', 's.name', 's.age')
            ->once()
            ->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('from')
            ->with('student', 's')
            ->once()
            ->andReturn($this->queryBuilder);

        $queryBuilder = $this->composer->compose($this->queryBuilder, $parts);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }
}
