<?php

namespace MF\QueryBuilderComposer\Tests\Functions;

use Doctrine\ORM\QueryBuilder;
use function MF\QueryBuilderComposer\Functions\isValidString;
use function MF\QueryBuilderComposer\Functions\singleStringRuleToRule;
use MF\QueryBuilderComposer\Tests\Provider\UtilProviderTrait;
use MF\QueryBuilderComposer\Tests\QueryBuilderComposerTestCase;
use Mockery as m;
use function MF\QueryBuilderComposer\Functions\isArray;
use function MF\QueryBuilderComposer\Functions\isValidArray;
use function MF\QueryBuilderComposer\Functions\sanitizeRule;
use function MF\QueryBuilderComposer\Functions\singleStringPartToPart;

class UtilFunctionsTest extends QueryBuilderComposerTestCase
{
    use UtilProviderTrait;

    /** @var QueryBuilder|m\MockInterface */
    private $queryBuilder;

    public function setUp()
    {
        $this->queryBuilder = m::mock(QueryBuilder::class);
    }

    /**
     * @dataProvider isValidArrayProvider
     */
    public function testShouldValidateArray($array, bool $isValid)
    {
        $this->assertSame($isValid, isValidArray($array));
    }

    /**
     * @dataProvider isArrayProvider
     */
    public function testShouldCheckArray($array, bool $isValid)
    {
        $this->assertSame($isValid, isArray($array));
    }

    /**
     * @dataProvider singleStringPartToPartProvider
     */
    public function testShouldMakePartFromSingleStringPart(string $part, array $expected)
    {
        $this->assertSame($expected, singleStringPartToPart($part));
    }

    /**
     * @dataProvider sanitizeRuleProvider
     */
    public function testShouldSanitizeRule(array $rule, array $expectedRule)
    {
        $this->assertSame($expectedRule, sanitizeRule($rule));
    }

    /**
     * @dataProvider singleStringRuleToRuleProvider
     */
    public function testShouldMakeRuleFromSingleStringRule(string $rule, array $expectedRule)
    {
        $this->assertSame($expectedRule, singleStringRuleToRule($rule));
    }

    /**
     * @dataProvider isValidStringProvider
     */
    public function testShouldValidateString($string, bool $isValid)
    {
        $this->assertSame($isValid, isValidString($string));
    }
}
