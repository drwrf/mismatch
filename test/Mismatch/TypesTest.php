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
}
