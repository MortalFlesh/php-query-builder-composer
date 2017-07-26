<?php

namespace MF\QueryBuilderComposer\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

abstract class QueryBuilderComposerTestCase extends TestCase
{
    protected function tearDown()
    {
        m::close();
    }
}
