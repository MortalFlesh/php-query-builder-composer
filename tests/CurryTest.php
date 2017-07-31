<?php

namespace MF\QueryBuilderComposer\Tests;

use Doctrine\ORM\QueryBuilder;
use MF\QueryBuilderComposer\Curry;
use MF\QueryBuilderComposer\Tests\Provider\MainProviderTrait;
use Mockery as m;

class CurryTest extends QueryBuilderComposerTestCase
{
    use MainProviderTrait;

    /** @var QueryBuilder|m\MockInterface */
    private $queryBuilder;

    public function setUp()
    {
        $this->queryBuilder = m::mock(QueryBuilder::class);
    }

    /**
     * @dataProvider composeProvider
     */
    public function testShouldCurryCompose(array $parts, ?callable $mocker)
    {
        if ($mocker) {
            $mocker($this->queryBuilder);
        }

        $compose = Curry::compose();
        $composeParts = $compose($parts);

        $queryBuilder = $composeParts($this->queryBuilder);

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

        $applyPart = Curry::applyPart();
        $applyPartToQueryBuilder = $applyPart($this->queryBuilder);

        $queryBuilder = $applyPartToQueryBuilder($part);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider applyModifierProvider
     */
    public function testShouldApplyModifierToQueryBuilder(callable $modifier, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $applyModifier = Curry::applyModifier();
        $applyModifierToQueryBuilder = $applyModifier($this->queryBuilder);

        $queryBuilder = $applyModifierToQueryBuilder($modifier);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider ruleProvider
     */
    public function testShouldApplyRule($rule, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $applyRule = Curry::applyRule();
        $applyRuleToQueryBuilder = $applyRule($this->queryBuilder);

        $queryBuilder = $applyRuleToQueryBuilder($rule);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider modifierProvider
     */
    public function testShouldAddToQueryBuilderByModifier(bool $append, string $dqlPartName, $dqlPart, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $modifier = Curry::modifier();
        $specificModifier = $modifier($append)($dqlPartName)($dqlPart);

        $queryBuilder = $specificModifier($this->queryBuilder);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider appendModifierProvider
     */
    public function testShouldAddToQueryBuilderByAppendModifier(string $dqlPartName, $dqlPart, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $modifierAppend = Curry::modifierAppend();
        $specificModifierAppend = $modifierAppend($dqlPartName)($dqlPart);

        $queryBuilder = $specificModifierAppend($this->queryBuilder);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }

    /**
     * @dataProvider setModifierProvider
     */
    public function testShouldAddToQueryBuilderBySetModifier(string $dqlPartName, $dqlPart, callable $mocker)
    {
        $mocker($this->queryBuilder);

        $modifierSet = Curry::modifierSet();
        $specificModifierSet = $modifierSet($dqlPartName)($dqlPart);

        $queryBuilder = $specificModifierSet($this->queryBuilder);

        $this->assertSame($this->queryBuilder, $queryBuilder);
    }
}
