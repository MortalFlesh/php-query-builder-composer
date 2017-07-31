<?php

namespace MF\QueryBuilderComposer\Tests\Provider;

trait UtilProviderTrait
{
    public function isValidArrayProvider()
    {
        return [
            'null' => [null, false],
            'string' => ['string', false],
            'empty' => [[], false],
            'array with item' => [['string'], true],
            'string[]' => [['string', 'string'], true],
            'array with array' => [[['string'], 'string'], true],
        ];
    }

    public function isArrayProvider()
    {
        return [
            'null' => [null, false],
            'string' => ['string', false],
            'empty' => [[], true],
            'array with item' => [['string'], true],
            'string[]' => [['string', 'string'], true],
            'array with array' => [[['string'], 'string'], true],
        ];
    }

    public function singleStringPartToPartProvider()
    {
        return [
            'select' => ['select s.id', ['select s.id']],
            'from' => ['from student s', ['from student s']],
        ];
    }

    public function sanitizeRuleProvider()
    {
        return [
            'string []' => [['select', 's.id'], ['select', 's.id']],
            'single string in string []' => [['select s.id'], ['select', 's.id']],
        ];
    }

    public function singleStringRuleToRuleProvider()
    {
        return [
            'select' => ['select s.id', ['select', 's.id']],
            'from' => ['from student s', ['from', 'student', 's']],
        ];
    }

    public function isValidStringProvider()
    {
        return [
            'null' => [null, false],
            'empty string' => ['', false],
            'string' => ['string', true],
        ];
    }
}
