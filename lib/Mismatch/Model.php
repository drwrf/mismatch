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
        if (array_key_exists($name, $this->attrs)) {
            return $this->attrs[$name];
        }
    }

    /**
     * Sets an attribute on the model.
     *
     * @param  string  $name
     * @param  mixed   $value
     */
    public function __set($name, $value)
    {
        $this->attrs[$name] = $value;
    }
}
