<?php

namespace Mismatch;

use Pimple;

class Metadata extends Pimple
{
    /**
     * @var  Metadata[]
     */
    private static $instances = [];

    /**
     * Factory for getting a class' Metadata.
     *
     * This ensures that the same Metadata instance is re-used for
     * all instances of a class.
     *
     * @param   string  $class
     * @return  Metadata
     */
    public static function get($class)
    {
        if (!isset(static::$instances[$class])) {
            static::$instances[$class] = new Metadata($class);
        }

        return static::$instances[$class];
    }

    /**
     * @var  string  $class
     */
    private $class;

    /**
     * Constructor.
     *
     * @param   string  $class
     */
    public function __construct($class)
    {
        parent::__construct();

        $this->class = $class;
    }

    /**
     * Returns the FQCN that this metadata is for.
     *
     * @return  string
     */
    public function getClass()
    {
        return $this->class;
    }
}
