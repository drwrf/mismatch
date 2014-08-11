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
     * Hook for when this trait is used on a class.
     */
    public static function usingModel($m)
    {
        $m['attrs'] = function($m) {
            return new Attrs($m);
        };
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
     * Returns whether or not a value is set on the model.
     *
     * In all cases, this will return true so long as the attribute
     * is set on the model, regardless of whether or not it's null.
     *
     * @param  string  $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->hasAttr($name) || $this->entity->has($name);
    }

    /**
     * @return  string
     */
    public function __toString()
    {
        return sprintf('%s:%s', get_class($this), md5(spl_object_hash($this)));
    }

    /**
     * Reads a bare attribute on the model.
     *
     * @param  string  $name
     * @return mixed
     */
    private function read($name)
    {
        if ($this->hasAttr($name)) {
            return $this->attr($name)->read($this);
        }

        if ($this->entity->has($name)) {
            return $this->entity->read($name);
        }

        throw new UnknownAttrException($this, $name);
    }

    /**
     * Writes a bare attribute on the model.
     *
     * @param  string  $name
     * @param  mixed   $value
     */
    private function write($name, $value)
    {
        if ($this->hasAttr($name)) {
            return $this->attr($name)->write($this, $value);
        }

        return $this->entity->write($name, $value);
    }

    /**
     * Returns whether or not the model has an attribute associated with it.
     *
     * @param  string $name
     * @return bool
     */
    private function hasAttr($name)
    {
        try {
            $this->attr($name);
        } catch (UnknownAttrException $e) {
            return false;
        }

        return true;
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
