<?php

namespace Mismatch;

use Mockery;
use InvalidArgumentException;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->attrs = Mockery::mock('Mismatch\Attrs');
        $this->attrs->shouldReceive('get')
            ->andThrow(new InvalidArgumentException());

        $this->subject = new Model\Mock();
        $this->subject->setAttrs($this->attrs);
    }

    public function test_magicSet_setsValue()
    {
        $this->subject->username = 'happy-boy-22';
        $this->assertEquals('happy-boy-22', $this->subject->username);
    }

    public function test_magicGet_callsGetter()
    {
        $this->subject->firstName = 'Raun';
        $this->subject->lastName = 'Pearlman';

        $this->assertEquals('Raun Pearlman', $this->subject->fullName);
    }

    public function test_magicGet_callsSetter()
    {
        $this->subject->fullName = 'Whoopsi Goldberg';

        $this->assertEquals('Whoopsi', $this->subject->firstName);
        $this->assertEquals('Goldberg', $this->subject->lastName);
    }
}

namespace Mismatch\Model;

use Mismatch;

class Mock
{
    use Mismatch\Model;

    public function getFullName()
    {
        return $this->readValue('firstName') . ' ' . $this->readValue('lastName');
    }

    public function setFullName($value)
    {
        $parts = explode(' ', $value);

        $this->writeValue('firstName', $parts[0]);
        $this->writeValue('lastName', $parts[1]);
    }

    public function setAttrs($attrs)
    {
        $this->attrs = $attrs;
    }
}
