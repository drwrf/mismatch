<?php

namespace Mismatch\Attr;

abstract class Primitive extends Base
{
    /**
     * {@inheritDoc}
     */
    public function read($model)
    {
        $value = $model->readValue($this->key);

        if ($this->nullable && $value === null) {
            return null;
        }

        return $this->cast($value);
    }

    /**
     * {@inheritDoc}
     */
    public function write($model, $value)
    {
        if (!$this->nullable || $value !== null) {
            $value = $this->cast($value);
        }

        return $model->writeValue($this->key, $value);
    }

    /**
     * Should return the value casted to an appropriate type.
     *
     * @param  mixed  $value
     * @return mixed
     */
    abstract public function cast($value);
}
