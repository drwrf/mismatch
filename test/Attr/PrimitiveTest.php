<?php

namespace Mismatch\Attr;

use Mismatch\Entity;

class PrimitiveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return  array
     */
    public function allTypes()
    {
        $createType = function($class) {
            return [new $class(), (object) []];
        };

        return [
            $createType('Mismatch\Attr\Integer'),
            $createType('Mismatch\Attr\Float'),
            $createType('Mismatch\Attr\String'),
            $createType('Mismatch\Attr\Boolean'),
            $createType('Mismatch\Attr\Set'),
        ];
    }

    /**
     * @dataProvider  allTypes
     */
    public function testCast($subject, $model)
    {
        $this->assertNotNull($subject->read($model, 'foo'));
        $this->assertNotNull($subject->write($model, 'foo'));
        $this->assertNotNull($subject->serialize($model, 'foo', 'foo'));
        $this->assertNotNull($subject->deserialize($model, 'foo'));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testCast_nullable($subject, $model)
    {
        $subject->nullable = true;

        $this->assertNull($subject->read($model, null));
        $this->assertNull($subject->write($model, null));
        $this->assertNull($subject->serialize($model, null, null));
        $this->assertNull($subject->deserialize($model, null));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testCast_default($subject, $model)
    {
        $subject->default = 'foo';

        $this->assertEquals('foo', $subject->read($model, null));
    }
}
