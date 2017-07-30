<?php

namespace MF\QueryBuilderComposer\Tests\Functions;

use MF\QueryBuilderComposer\Tests\ProviderTrait;
use MF\QueryBuilderComposer\Tests\QueryBuilderComposerTestCase;
use function MF\QueryBuilderComposer\Functions\isModifier;
use function MF\QueryBuilderComposer\Functions\isPart;
use function MF\QueryBuilderComposer\Functions\isQueryBuilderMethod;
use function MF\QueryBuilderComposer\Functions\isRule;
use function MF\QueryBuilderComposer\Functions\isSingleStringPart;

class TypeFunctionsTest extends QueryBuilderComposerTestCase
{
    use ProviderTrait;

    /**
     * @dataProvider isPartProvider
     */
    public function testShouldCheckPartType($part, bool $expected)
    {
        $result = isPart($part);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider isModifierProvider
     */
    public function testShouldCheckModifierType($modifier, bool $expected)
    {
        $result = isModifier($modifier);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider isSingleStringPartProvider
     */
    public function testShouldCheckSingleStringPartType($rule, bool $expected)
    {
        $result = isSingleStringPart($rule);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider isRuleProvider
     */
    public function testShouldCheckRuleType($rule, bool $expected)
    {
        $result = isRule($rule);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider isQueryBuilderMethodProvider
     */
    public function testShouldCheckQueryBuilderMethodExists($method, bool $expected)
    {
        $result = isQueryBuilderMethod($method);

        $this->assertSame($expected, $result);
    }
}
