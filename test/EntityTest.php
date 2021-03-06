<?php

namespace Mismatch;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new Entity([
            'original' => true
        ]);
    }

    public function test_has_worksForOriginalValues()
    {
        $this->assertTrue($this->subject->has('original'));
        $this->assertFalse($this->subject->has('invalid'));
    }

    public function test_has_worksForChangedValues()
    {
        $this->subject->write('original', false);
        $this->assertTrue($this->subject->has('original'));
    }

    public function test_read_worksForOriginalValues()
    {
        $this->assertTrue($this->subject->read('original'));
    }

    public function test_read_worksForChangeValues()
    {
        $this->subject->write('original', false);
        $this->assertFalse($this->subject->read('original'));
    }

    public function test_change_worksForDifferentValues()
    {
        $this->subject->write('original', false);
        $this->assertTrue($this->subject->changed('original'));
    }

    public function test_write_ignoresSameValues()
    {
        $this->subject->write('original', true);
        $this->assertFalse($this->subject->changed('original'));
    }
}
