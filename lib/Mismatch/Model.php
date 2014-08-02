<?php

namespace Mismatch;

trait Model
{
    /**
     * @var  Metadata
     */
    private static $metadata;

    /**
     * @param  Metadata
     */
    public static function usingModel($m)
    {
        static::$metadata = $m;
    }

    /**
     * @var  array
     */
    protected $data;

    /**
     * Returns an attribute on the model.
     *
     * @param  string  $name
     * @return mixed
     */
    public function __get($name)
    {
        if (method_exists($this, 'get'.$name)) {
            return $this->{'get'.$name}();
        }

        return $this->read($name);
    }

    /**
     * Sets an attribute on the model.
     *
     * @param  string  $name
     * @param  mixed   $value
     */
    public function __set($name, $value)
    {
        if (method_exists($this, 'set'.$name)) {
            return $this->{'set'.$name}($value);
        }

        $this->write($name, $value);
    }

    /**
     * Returns whether or not the attribute has any value associated with it.
     *
     * @param  string $name
     * @return bool
     */
    public function exists($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Reads a bare attribute on the model.
     *
     * @param  string  $name
     * @return mixed
     */
    public function read($name)
    {
        if ($this->exists($name)) {
            return $this->data[$name];
        }
    }

    /**
     * Writes a bare attribute on the model.
     *
     * @param  string  $name
     * @param  mixed   $value
     */
    public function write($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Returns whether or not a value exists in the bag of data.
     *
     * @param   string  $name
     * @return  bool
     */
    public function hasValue($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Reads a value from the data on this model.
     *
     * This returns the bare value, without any processing by the attribute
     * or otherwise.
     *
     * @param   string  $name
     * @return  mixed
     */
    public function readValue($name)
    {
        if ($this->hasValue($name)) {
            return $this->data[$name];
        }
    }

    /**
     * Writes a value to the data on this model.
     *
     * This writes the bare value, without any processing by the attribute
     * or setters declared on the model.
     *
     * @param   string  $name
     * @param   mixed   $value
     * @return  mixed
     */
    public function writeValue($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }
}
