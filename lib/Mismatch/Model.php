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
     * @var  Mismatch\Entity
     */
    public $entity;

    /**
     * @var  Mismatch\Attrs
     */
    private $attrs;

    /**
     * @param   array  $data
     */
    public function __construct($data = [])
    {
        $this->entity = new Entity($data);
    }

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

        return $this->entity->read($name);
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
            return $this->attr($name)->write($this, $value);
        }

        return $this->entity->write($name, $value);
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
