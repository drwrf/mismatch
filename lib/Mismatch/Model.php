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
    protected $attrs;

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
    private function exists($name)
    {
        return array_key_exists($name, $this->attrs);
    }

    /**
     * Reads a bare attribute on the model.
     *
     * @param  string  $name
     * @return mixed
     */
    private function read($name)
    {
        if ($this->exists($name)) {
            return $this->attrs[$name];
        }
    }

    /**
     * Writes a bare attribute on the model.
     *
     * @param  string  $name
     * @param  mixed   $value
     */
    private function write($name, $value)
    {
        $this->attrs[$name] = $value;
    }
}
