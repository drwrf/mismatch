<?php

namespace Mismatch\Attr;

abstract class Primitive extends Base
{
    /**
     * {@inheritDoc}
     */
    public function read($model, $value)
    {
        return $this->cast($value);
    }

    /**
     * {@inheritDoc}
     */
    public function write($model, $value)
    {
        return $this->cast($value);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize($model, $value)
    {
        return $this->cast($value);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize($result, $value)
    {
        return $this->cast($value);
    }

    /**
     * Should return the default value for the type.
     *
     * @return mixed
     */
    public function getDefault($model)
    {
        return $this->default;
    }

    /**
     * Should return the value casted to an appropriate type.
     *
     * @param  mixed  $value
     * @return mixed
     */
    abstract public function cast($value);
}
