<?php

namespace Mismatch\Type;

class NativeTypeTest extends \PHPUnit_Framework_TestCase
{
    public static function provideIntegers()
    {
        return [
            [ true, 1 ],
            [ false, 1.0 ],
            [ false, false ],
            [ false, '1' ],
        ];
    }

    /**
     * @dataProvider  provideIntegers
     */
    public function test_valid_integer($expected, $value)
    {
        $type = new NativeType('integer');
        $this->assertEquals($expected, $type->valid($value));
    }

    public static function provideFloats()
    {
        return [
            [ false, 1 ],
            [ true, 1.0 ],
            [ false, false ],
            [ false, '1' ],
        ];
    }

    /**
     * @dataProvider  provideFloats
     */
    public function test_valid_float($expected, $value)
    {
        $type = new NativeType('float');
        $this->assertEquals($expected, $type->valid($value));
    }

    public static function provideBooleans()
    {
        return [
            [ false, 1 ],
            [ false, 1.0 ],
            [ true, false ],
            [ false, '1' ],
        ];
    }

    /**
     * @dataProvider  provideBooleans
     */
    public function test_valid_boolean($expected, $value)
    {
        $type = new NativeType('boolean');
        $this->assertEquals($expected, $type->valid($value));
    }

    public static function provideStrings()
    {
        return [ 
            [ false, 1 ],
            [ false, 1.0 ],
            [ false, false ],
            [ true, '1' ],
        ];
    }

    /**
     * @dataProvider  provideStrings
     */
    public function test_valid_string($expected, $value)
    {
        $type = new NativeType('string');
        $this->assertEquals($expected, $type->valid($value));
    }

    public static function provideNulls()
    {
        return [
            [ false, 1 ],
            [ false, 1.0 ],
            [ false, false ],
            [ false, '1' ],
            [ true, null ],
        ];
    }

    /**
     * @dataProvider  provideNulls
     */
    public function test_valid_null($expected, $value)
    {
        $type = new NativeType('null');
        $this->assertEquals($expected, $type->valid($value));
    }

    /**
     * @expectedException  InvalidArgumentException
     */
    public function test_construct_invalidType()
    {
        new NativeType('invalid');
    }
}
