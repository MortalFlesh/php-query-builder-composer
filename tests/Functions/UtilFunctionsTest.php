<?php

namespace MF\QueryBuilderComposer\Tests\Functions;

use Doctrine\ORM\QueryBuilder;
use MF\QueryBuilderComposer\Tests\Provider\UtilProviderTrait;
use MF\QueryBuilderComposer\Tests\QueryBuilderComposerTestCase;
use Mockery as m;
use function MF\QueryBuilderComposer\Functions\isValidArray;

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
        $result = isValidArray($array);

        $this->assertSame($isValid, $result);
    }
}
