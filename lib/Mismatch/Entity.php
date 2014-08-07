<?php

namespace Mismatch;

class Entity
{
    /**
     * @var  array  The raw array of database values.
     */
    private $data = [];

    /**
     * @var  array  The changed values on the entity.
     */
    private $changes = [];

    /**
     * @param  array  $data  The original set of data for the entity.
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Reads a value from the entity.
     *
     * @param   string  $name
     * @return  bool
     */
    public function has($name)
    {
        if (array_key_exists($name, $this->changes)) {
            return true;
        }

        if (array_key_exists($name, $this->data)) {
            return true;
        }

        return false;
    }

    /**
     * Reads a value from the entity.
     *
     * @param   string  $name
     * @return  mixed
     */
    public function read($name)
    {
        if (array_key_exists($name, $this->changes)) {
            return $this->changes[$name];
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    /**
     * Changes a value or set of values on the entity.
     *
     * @param   string  $data
     * @param   mixed   $value
     * @return  $this
     */
    public function write($name, $value)
    {
        $this->changes[$name] = $value;

        return $this;
    }
}
