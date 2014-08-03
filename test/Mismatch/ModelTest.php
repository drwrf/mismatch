<?php

namespace Mismatch;

use Mockery;
use Mismatch\Exception\UnknownAttrException;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->attrs = Mockery::mock('Mismatch\Attrs');
        $this->attrs->shouldReceive('get')
            ->andThrow(new UnknownAttrException());

        $this->subject = new Model\Mock();
        $this->subject->setAttrs($this->attrs);
    }

    public function test_constructor_acceptsData()
    {
        $subject = new Model\Mock([
            'foo' => 'bar'
        ]);

        $this->assertEquals('bar', $subject->foo);
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
        return $this->read('firstName') . ' ' . $this->read('lastName');
    }

    public function setFullName($value)
    {
        $parts = explode(' ', $value);

        $this->write('firstName', $parts[0]);
        $this->write('lastName', $parts[1]);
    }

    public function setAttrs($attrs)
    {
        $this->attrs = $attrs;
    }
}
