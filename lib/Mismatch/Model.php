<?php

namespace Mismatch;

use Mismatch\Exception\UnknownAttrException;

trait Model
{
    /**
     * @return  Mismatch\Metadata
     */
    public static function metadata()
    {
        return Metadata::get(get_called_class());
    }

    /**
     * @var  array
     */
    protected $data = [];

    /**
     * @var  Mismatch\Attrs
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

        return $this->write($name, $value);
    }

    /**
     * Returns whether or not the model has an attribute associated with it.
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        try {
            $this->attr($name);
        } catch (UnknownAttrException $e) {
            return false;
        }

        return true;
    }

    /**
     * Reads a bare attribute on the model.
     *
     * @param  string  $name
     * @return mixed
     */
    public function read($name)
    {
        if ($this->has($name)) {
            return $this->attr($name)->read($this);
        }

        return $this->readValue($name);
    }

    /**
     * Writes a bare attribute on the model.
     *
     * @param  string  $name
     * @param  mixed   $value
     */
    public function write($name, $value)
    {
        if ($this->has($name)) {
            $this->attr($name)->write($this, $value);
        }

        return $this->writeValue($name, $value);
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

    /**
     * Returns an attribute instance for this model.
     *
     * @return  Mismatch\Attr\AttrInterface
     */
    private function attr($name)
    {
        if (!$this->attrs) {
            $this->attrs = static::metadata()['attrs'];
        }

        return $this->attrs->get($name);
    }
}
