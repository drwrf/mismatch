<?php

namespace Mismatch\Integration;

use Mismatch\Mock;

class PrimitivesTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new Mock\Primitives();
    }

    public function testSetters()
    {
        $this->subject->firstName = 'Peeder';
        $this->subject->lastName = 'Pam';
        $this->subject->active = 1;

        $this->assertEquals('Peeder Pam', $this->subject->name);
        $this->assertTrue($this->subject->active);
    }

    public function testGetters()
    {
        $this->assertEquals('', $this->subject->firstName);
        $this->assertEquals('', $this->subject->lastName);
        $this->assertEquals(0, $this->subject->logins);
        $this->assertFalse($this->subject->active);
    }
}

namespace Mismatch\Mock;

use Mismatch;

class Primitives
{
    use Mismatch\Model;

    public static function init($m)
    {
        $m->firstName = 'String';
        $m->lastName = 'String';
        $m->logins = 'Integer';
        $m->active = 'Boolean';
    }

    private function getName()
    {
        return join([$this->firstName, $this->lastName], ' ');
    }
}
