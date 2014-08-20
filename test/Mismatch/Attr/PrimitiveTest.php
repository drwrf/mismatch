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
            $entity = new Entity();
            $model = (object) ['entity' => $entity];

            return [new $class(), $model, $entity];
        };

        return [
            $createType('Mismatch\Attr\Integer'),
            $createType('Mismatch\Attr\Float'),
            $createType('Mismatch\Attr\String'),
            $createType('Mismatch\Attr\Boolean'),
            $createType('Mismatch\Attr\Time'),
            $createType('Mismatch\Attr\Set'),
        ];
    }

    /**
     * @dataProvider  allTypes
     */
    public function testRead($subject, $model, $entity)
    {
        $subject->name = 'foo';
        $entity->write('foo', 'now');
        $this->assertNotNull($subject->read($model));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testRead_whenNullable($subject, $model, $entity)
    {
        $subject->nullable = true;
        $subject->name = 'foo';
        $entity->write('foo', null);
        $this->assertNull($subject->read($model));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testRead_default_whenNotNullable($subject, $model, $entity)
    {
        $subject->name = 'foo';
        $subject->default = 'now';
        $this->assertNotNull('now', $subject->read($model));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testRead_default_whenNullable($subject, $model, $entity)
    {
        $subject->name = 'foo';
        $subject->nullable = true;
        $this->assertNull($subject->read($model));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testWrite($subject, $model, $entity)
    {
        $this->assertFalse($entity->has('foo'));

        $subject->name = 'foo';
        $subject->nullable = true;
        $subject->write($model, 'now');

        $this->assertTrue($entity->has('foo'));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testWrite_whenNullable($subject, $model, $entity)
    {
        $this->assertFalse($entity->has('foo'));

        $subject->name = 'foo';
        $subject->nullable = true;
        $subject->write($model, null);

        $this->assertTrue($entity->has('foo'));
        $this->assertNull($entity->read('foo'));
    }

    /**
     * @dataProvider  allTypes
     */
    public function testDeserialize_whenExists($subject, $model, $entity)
    {
        $subject->key = 'foo';
        $subject->name = 'bar';

        $this->assertEquals(['bar' => 'baz'], $subject->deserialize([
            'foo' => 'baz'
        ]));
    }
}
