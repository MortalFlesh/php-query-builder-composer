<?php

namespace MF\QueryBuilderComposer\Tests;

use Doctrine\ORM\QueryBuilder;
use Mockery as m;
use function MF\QueryBuilderComposer\applyModifier;
use function MF\QueryBuilderComposer\applyPart;
use function MF\QueryBuilderComposer\applyRule;
use function MF\QueryBuilderComposer\compose;
use function MF\QueryBuilderComposer\mergePartGroups;
use function MF\QueryBuilderComposer\modifier;
use function MF\QueryBuilderComposer\modifierAppend;
use function MF\QueryBuilderComposer\modifierSet;
use function MF\QueryBuilderComposer\sanitizeRule;

class FunctionsTest extends QueryBuilderComposerTestCase
{
    use ProviderTrait;

    /** @var QueryBuilder|m\MockInterface */
    private $queryBuilder;

    public function setUp()
    {
        $this->queryBuilder = m::mock(QueryBuilder::class);
    }

    /**
     * @dataProvider composeProvider
     */
    public function testShouldCompose(array $parts, ?callable $mocker)
    {
        if ($mocker) {
            $mocker($this->queryBuilder);
        }

        $queryBuilder = compose($parts, $this->queryBuilder);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider applyPartProvider
     */
    public function testShouldApplyPart($part, callable $mocker)
    {
        if ($mocker) {
            $mocker($this->queryBuilder);
        }

        $queryBuilder = applyPart($this->queryBuilder, $part);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider invalidPartProvider
     */
    public function testShouldThrowInvalidArgumentExceptionGivenToApplyPart($invalidPart)
    {
        $this->expectException(\InvalidArgumentException::class);

        applyPart($this->queryBuilder, $invalidPart);
    }

    /**
     * @dataProvider applyModifierProvider
     */
    public function testShouldApplyModifierToQueryBuilder(callable $modifier, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $queryBuilder = applyModifier($this->queryBuilder, $modifier);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider ruleProvider
     */
    public function testShouldApplyRule($rule, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $queryBuilder = applyRule($this->queryBuilder, $rule);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider sanitizeRuleProvider
     */
    public function testShouldSanitizeRule(array $rule, array $expectedRule)
    {
        $result = sanitizeRule($rule);

        $this->assertSame($expectedRule, $result);
    }

    /**
     * @dataProvider modifierProvider
     */
    public function testShouldAddToQueryBuilderByModifier(bool $append, string $dqlPartName, $dqlPart, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $queryBuilder = modifier($append, $dqlPartName, $dqlPart, $this->queryBuilder);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider appendModifierProvider
     */
    public function testShouldAddToQueryBuilderByAppendModifier(string $dqlPartName, $dqlPart, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $queryBuilder = modifierAppend($dqlPartName, $dqlPart, $this->queryBuilder);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider setModifierProvider
     */
    public function testShouldAddToQueryBuilderBySetModifier(string $dqlPartName, $dqlPart, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $queryBuilder = modifierSet($dqlPartName, $dqlPart, $this->queryBuilder);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider mergeGroupPartsProvider
     */
    public function testShouldMergeGroupParts(array $groupParts, array $expected)
    {
        $parts = mergePartGroups($groupParts);

        $this->assertEquals($expected, $parts);
    }
}
