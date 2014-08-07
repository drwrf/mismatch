<?php

namespace Mismatch\ORM\Expression;

class CompositeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->subject = new Composite('test');
    }

    public function test_string()
    {
        $this->subject->all('name = ?', ['test'])
                      ->any('name is null');

        $expr = $this->subject->getExpression();
        $binds = $this->subject->getValues();

        $this->assertEquals('name = ? OR name is null', $this->subject->getExpression());
        $this->assertEquals(['test'], $this->subject->getValues());
    }

    public function test_arrayEq()
    {
        $this->subject->all([ 'name' => 'test' ])
                      ->any([ 'foo' => 'bar' ]);

        $expr = $this->subject->getExpression();
        $binds = $this->subject->getValues();

        $this->assertEquals('test.name = ? OR test.foo = ?', $expr);
        $this->assertEquals(['test', 'bar'], $binds);
    }

    public function test_arrayIn()
    {
        $this->subject->all([ 'name' => ['test']])
                      ->any([ 'foo' => ['bar']]);

        $expr = $this->subject->getExpression();
        $binds = $this->subject->getValues();

        $this->assertEquals('test.name IN ? OR test.foo IN ?', $expr);
        $this->assertEquals([['test'], ['bar']], $binds);
    }

    public function test_comparator()
    {
        $this->subject->all([ 'name' => new Eq('test')])
                      ->any([ 'foo' => new Eq('bar')]);

        $expr = $this->subject->getExpression();
        $binds = $this->subject->getValues();

        $this->assertEquals('test.name = ? OR test.foo = ?', $expr);
        $this->assertEquals(['test', 'bar'], $binds);
    }
}
