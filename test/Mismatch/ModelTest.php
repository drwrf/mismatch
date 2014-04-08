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
}

namespace Mismatch\Model;

use Mismatch;

class Mock
{
    use Mismatch\Model;
}
