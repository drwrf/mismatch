<?php

namespace Mismatch;

class TypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException  BadMethodCallException
     */
    public function test_callStatic_unknownType()
    {
        Types::invalid();
    }

    public function test_register_indirectly()
    {
        $this->assertInstanceOf('Mismatch\Type\NativeType', Types::integer());
    }
}
