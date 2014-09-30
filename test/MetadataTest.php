<?php

namespace Mismatch;

class MetadataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = Metadata::get('Mismatch\Metadata\Mock');
    }

    public function test_get_returnsSameInstance()
    {
        $this->assertSame($this->subject, Metadata::get('Mismatch\Metadata\Mock'));
    }

    public function test_getClass_returnsClass()
    {
        $this->assertEquals('Mismatch\Metadata\Mock', $this->subject->getClass());
    }

    public function test_getNamespace_returnsNamespace()
    {
        $this->assertEquals('Mismatch\Metadata', $this->subject->getNamespace());
    }

    public function test_getParents_returnsArray()
    {
        $this->assertEquals([
            'Mismatch\Metadata\MockGrandParent',
            'Mismatch\Metadata\MockParent',
        ], $this->subject->getParents());
    }

    public function test_getTraits_returnsArray()
    {
        $this->assertEquals([
            'Mismatch\Metadata\MockNestedTrait',
            'Mismatch\Metadata\MockTrait',
            'Mismatch\Metadata\MockParentTrait',
        ], $this->subject->getTraits());
    }

    public function test_constructor_callsInit()
    {
        $this->assertSame($this->subject, Metadata\Mock::$calledWith);
    }
}

namespace Mismatch\Metadata;

trait MockNestedTrait
{

}

trait MockTrait
{
    use MockNestedTrait;
}

trait MockParentTrait
{

}

class MockGrandParent
{
    use MockTrait;
}

class MockParent extends MockGrandParent
{
    use MockParentTrait;
}

class Mock extends MockParent
{
    use MockTrait;

    public static $calledWith;

    public static function init($m)
    {
        self::$calledWith = $m;
    }
}
