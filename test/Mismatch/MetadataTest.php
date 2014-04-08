<?php

namespace Mismatch;

class MetadataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = Metadata::get('Mismatch\MetadataTest');
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

    public function test_constructor_callsInit()
    {
        $this->assertSame(Metadata::get('Mismatch\Mock\Metadata'), Mock\Metadata::$calledWith);
    }
}

namespace Mismatch\Mock;

class Metadata
{
    public static $calledWith;

    public static function init($m)
    {
        self::$calledWith = $m;
    }
}
