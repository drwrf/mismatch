<?php

namespace Mismatch;

use Pimple;
use ReflectionClass;

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
     * @var  string
     */
    private $class;

    /**
     * @var  string
     */
    private $ns;

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
     * Adds an attribute to the Metadata instance.
     *
     * @param   string  $name
     * @param   mixed   $type
     */
    public function __set($name, $type)
    {
        // TODO
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

    /**
     * The namespace that this class is contained within.
     *
     * @return  string
     */
    public function getNamespace()
    {
        if (!$this->ns) {
            $this->ns = (new ReflectionClass($this->getClass()))
                ->getNamespaceName();
        }

        return $this->ns;
    }
}
