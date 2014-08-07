<?php

namespace Mismatch\ORM\Expression;

class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function test_basicUsage()
    {
        $expr = new Expression('NOT (%s = ?)', ['test']);
        $this->assertEquals('NOT (col = ?)', $expr->getExpression('col'));
        $this->assertEquals(['test'], $expr->getValues());
    }
}
