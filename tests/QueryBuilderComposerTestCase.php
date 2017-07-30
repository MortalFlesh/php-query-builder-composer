<?php

namespace MF\QueryBuilderComposer\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

abstract class QueryBuilderComposerTestCase extends TestCase
{
    const MODIFIER_APPEND = true;

    protected function tearDown()
    {
        m::close();
    }
}
