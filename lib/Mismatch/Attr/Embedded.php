<?php

namespace Mismatch\Attr;

use InvalidArgumentException;

class Embedded extends Base
{
    protected $class;

    /**
     * {@inheritDoc}
     */
    public function read($model)
    {
        $value = $model->readValue($this->key);

        if ($this->nullable && $value === null) {
            return null;
        }

        if (!($value instanceof $this->class)) {
            // Write this back to the class so we have a reference
            // to the value the next time around. We don't want to
            // create many instances of the same value object.
            $value = new $this->class($value);
            $model->writeValue($this->key, $value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function write($model, $value)
    {
        if (!($value instanceof $this->class)) {
            throw new InvalidArgumentException();
        }

        return $model->writeValue($this->key, $value);
    }
}
