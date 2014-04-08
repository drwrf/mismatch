<?php

namespace Mismatch;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new Model\Mock();
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
}
