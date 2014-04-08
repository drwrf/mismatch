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
     * @var  ReflectionClass
     */
    private $class;

    /**
     * @var  array
     */
    private $parents;

    /**
     * @var  array
     */
    private $traits;

    /**
     * Constructor.
     *
     * @param   string  $class
     */
    public function __construct($class)
    {
        parent::__construct();

        $this->class = new ReflectionClass($class);

       // Allow traits to define callbacks that run when included in a model.
        foreach ($this->getTraits() as $trait) {
            if ($method = $this->methodForTrait($trait)) {
                call_user_func($method, $this);
            }
        }

        // At this point, it's time to initialize the class and let it
        // declare any properties or other metadata that it wants.
        if (method_exists($class, 'init') && is_callable([$class, 'init'])) {
            $class::init($this);
        }
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
        return $this->class->getName();
    }

    /**
     * The namespace that this class is contained within.
     *
     * @return  string
     */
    public function getNamespace()
    {
        return $this->class->getNamespaceName();
    }

    /**
     * Returns the parents of the class.
     *
     * Parents will be returned in order of evaluation, which means
     * that your most distant relative will be first in the list
     * and your direct parent will be last.
     *
     * @return  array
     */
    public function getParents()
    {
        if (!$this->parents) {
            $parents = class_parents($this->getClass());
            $parents = array_reverse($parents);
            $parents = array_keys($parents);
            $this->parents = $parents;
        }

        return $this->parents;
    }

    /**
     * A list of traits that the class uses.
     *
     * Just like `getParents`, the list of traits is returned in order of
     * evaluation. Traits are also recursively discovered, which means that
     * if a trait your class uses also uses a trait, that will be included
     * in the list.
     *
     * @return  array
     */
    public function getTraits()
    {
        if (!$this->traits) {
            $traits = [];

            foreach ($this->getParents() as $parent) {
                $traits = array_merge($traits, $this->listTraits($parent));
            }

            // Include traits for the class we're actually working with.
            $traits = array_merge($traits, $this->listTraits($this->getClass()));
            $traits = array_unique($traits);
            $this->traits = $traits;
        }

        return $this->traits;
    }

    /**
     * @param   string  $class
     * @return  array
     */
    private function listTraits($class)
    {
        $traits = [];

        foreach (class_uses($class) as $trait) {
            $traits = array_merge($traits, $this->listTraits($trait), [$trait]);
        }

        return $traits;
    }

    /**
     * @return  string
     */
    private function methodForTrait($trait)
    {
        $method = substr($trait, strrpos($trait, '\\') + 1);
        $method = 'using' . $method;

        // We need both the method_exists and is_callable to ensure
        // that the method is *actually* defined. Calling a method
        // with __callStatic is not allowed and generally bad.
        if (method_exists($this->getClass(), $method) && is_callable($callable)) {
            return [$this->getClass(), $method];
        }
    }
}
