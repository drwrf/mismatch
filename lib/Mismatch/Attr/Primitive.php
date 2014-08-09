<?php

namespace Mismatch\Attr;

abstract class Primitive extends Base
{
    /**
     * {@inheritDoc}
     */
    public function read($model)
    {
        if (!$this->hasValue($model)) {
            return !$this->nullable ? $this->getDefault() : null;
        }

        $value = $this->readValue($model);

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

        return $this->writeValue($model, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $result)
    {
        if (array_key_exists($this->key, $result)) {
            return [$this->name => $result[$this->key]];
        }
    }

    /**
     * Should return the default value for the type.
     *
     * @return mixed
     */
    public function getDefault()
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
