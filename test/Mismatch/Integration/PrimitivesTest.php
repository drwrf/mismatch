<?php

namespace Mismatch\Integration;

use Mismatch\Metadata;
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

        $this->assertSame('Peeder Pam', $this->subject->name);
        $this->assertTrue($this->subject->active);
    }

    public function testGetters()
    {
        $this->assertSame(null, $this->subject->firstName);
        $this->assertSame(null, $this->subject->lastName);
        $this->assertSame(0, $this->subject->logins);
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
        $m->firstName = 'String?';
        $m->lastName = 'String?';
        $m->logins = 'Integer';
        $m->active = 'Boolean';
    }

    private function getName()
    {
        return join([$this->firstName, $this->lastName], ' ');
    }
}
