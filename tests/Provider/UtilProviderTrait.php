<?php

namespace MF\QueryBuilderComposer\Tests\Provider;

trait UtilProviderTrait
{
    public function isValidArrayProvider()
    {
        return [
            'not array' => [null, false],
            'empty' => [[], false],
            'array with item' => [['string'], true],
            'string[]' => [['string', 'string'], true],
            'array with array' => [[['string'], 'string'], true],
        ];
    }
}
