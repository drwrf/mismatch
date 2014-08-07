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
        list($expr, $binds) = $this->subject->all('name = ?', ['test'])
                                            ->any('name is null')
                                            ->compile();

        $this->assertEquals('name = ? OR name is null', $expr);
        $this->assertEquals(['test'], $binds);
    }

    public function test_arrayEq()
    {
        list($expr, $binds) = $this->subject->all([ 'name' => 'test' ])
                                            ->any([ 'foo' => 'bar' ])
                                            ->compile();

        $this->assertEquals('test.name = ? OR test.foo = ?', $expr);
        $this->assertEquals(['test', 'bar'], $binds);
    }

    public function test_arrayIn()
    {
        list($expr, $binds) = $this->subject->all([ 'name' => ['test']])
                                            ->any([ 'foo' => ['bar']])
                                            ->compile();

        $this->assertEquals('test.name IN ? OR test.foo IN ?', $expr);
        $this->assertEquals([['test'], ['bar']], $binds);
    }

    public function test_comparator()
    {
        list($expr, $binds) = $this->subject->all([ 'name' => new Eq('test')])
                                            ->any([ 'foo' => new Eq('bar')])
                                            ->compile();

        $this->assertEquals('test.name = ? OR test.foo = ?', $expr);
        $this->assertEquals(['test', 'bar'], $binds);
    }
}
