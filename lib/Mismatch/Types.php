<?php

namespace Mismatch;

use BadMethodCallException;

class Types
{
    /**
     * @var  Type[]  A list of known type instances.
     */
    private static $types = [];

    /**
     * Affords referencing a type as though it's a method on this class.
     *
     * If a single argument is passed then the `valid` method on the
     * type will be automatically called, affording easy type validation.
     *
     * If no argument is passed, then the type instance is returned.
     *
     * @param   string  $method
     * @param   array   $args
     * @return  mixed
     */
    public static function __callStatic($method, $args)
    {
        if (!isset(self::$types[$method])) {
            throw new BadMethodCallException(sprintf(
                'Could not locate the type "%s". Are you sure that
                 you have properly registered the type?',
                 $method));
        }

        $type = self::$types[$method];

        if (array_key_exists(0, $args)) {
            return $type->valid($args[0]);
        }

        return $type;
    }

    /**
     * Adds a new type to the list of types.
     *
     * @param  string         $name
     * @param  TypeInterface  $type
     */
    public static function register($name, TypeInterface $type)
    {
        self::$types[$name] = $type;
    }
}

/**
 * Registers all of the default types that Mismatch
 * always chooses to expose.
 */
Types::register('int',     new Type\NativeType('integer'));
Types::register('integer', new Type\NativeType('integer'));
Types::register('double',  new Type\NativeType('float'));
Types::register('float',   new Type\NativeType('float'));
Types::register('bool',    new Type\NativeType('boolean'));
Types::register('boolean', new Type\NativeType('boolean'));
Types::register('str',     new Type\NativeType('string'));
Types::register('string',  new Type\NativeType('string'));
Types::register('text',    new Type\NativeType('string'));
