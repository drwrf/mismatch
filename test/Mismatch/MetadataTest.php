<?php

namespace Mismatch;

class MetadataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new Metadata('StdClass');
    }

    public function test_getClass_returnsClass()
    {
        $this->assertEquals('StdClass', $this->subject->getClass());
    }
}
