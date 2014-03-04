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
}
