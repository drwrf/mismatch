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
        $this->subject->firstName = 'Stephen';
        $this->subject->lastName = 'Snell';
        $this->subject->active = 1;
        $this->subject->tags = ['murphy-brown'];
        $this->subject->rating = 4.4;

        $this->assertSame('Stephen Snell', $this->subject->name);
        $this->assertSame(['murphy-brown'], $this->subject->tags);
        $this->assertSame(4.4, $this->subject->rating);
        $this->assertTrue($this->subject->active);
    }

    public function testGetters()
    {
        $this->assertSame(null, $this->subject->firstName);
        $this->assertSame(null, $this->subject->lastName);
        $this->assertSame(0, $this->subject->logins);
        $this->assertSame(0.0, $this->subject->rating);
        $this->assertSame([], $this->subject->tags);
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
        $m->rating = 'Float';
        $m->tags = 'Set';
    }

    private function getName()
    {
        return join([$this->firstName, $this->lastName], ' ');
    }
}
