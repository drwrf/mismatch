<?php

namespace Mismatch;

class MetadataTest extends \PHPUnit_Framework_TestCase
{
    private static $initArgs;

    public function setUp()
    {
        $this->subject = Metadata::get('Mismatch\MetadataTest');
    }

    public function init($m)
    {
        self::$initArgs = $m;
    }

    public function test_get_returnsSameInstance()
    {
        $this->assertSame($this->subject, Metadata::get('Mismatch\MetadataTest'));
    }

    public function test_getClass_returnsClass()
    {
        $this->assertEquals('Mismatch\MetadataTest', $this->subject->getClass());
    }

    public function test_getNamespace_returnsNamespace()
    {
        $this->assertEquals('Mismatch', $this->subject->getNamespace());
    }

    public function test_getParents_returnsArray()
    {
        $this->assertEquals([
            'PHPUnit_Framework_Assert',
            'PHPUnit_Framework_TestCase',
        ], $this->subject->getParents());
    }

    public function test_getTraits_returnsArray()
    {
        // TODO: this needs a beefier test.
        $this->assertEquals([], $this->subject->getTraits());
    }

    public function test_constructor_callsInit()
    {
        $this->assertSame($this->subject, self::$initArgs);
    }
}
