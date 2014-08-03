<?php

namespace Mismatch\Integration;

use Mismatch\Mock\EmbeddedUser as User;

class EmbeddedTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new User([
            'address' => [
                'street' => '308 Negra Arroyo Lane',
                'city' => 'Albuquerque',
                'state' => 'New Mexico',
            ]
        ]);
    }

    public function testGet_returnsInstance()
    {
        $address = $this->subject->address;

        $this->assertEquals('308 Negra Arroyo Lane', $address->street);
        $this->assertEquals('Albuquerque', $address->city);
        $this->assertEquals('New Mexico', $address->state);
        $this->assertInstanceOf('Mismatch\Mock\EmbeddedAddress', $address);
    }
}

namespace Mismatch\Mock;

use Mismatch;

class EmbeddedUser
{
    use Mismatch\Model;

    public static function init($m)
    {
        $m->address = 'Mismatch\Mock\EmbeddedAddress';
    }
}

class EmbeddedAddress
{
    use Mismatch\Model;

    public static function init($m)
    {
        $m->street = 'String';
        $m->city   = 'String';
        $m->state  = 'String';
    }
}
