<?php

namespace Mismatch\Attr;

abstract class Primitive extends Base
{
    /**
     * {@inheritDoc}
     */
    public function read($model)
    {
        if (!$this->hasValue($model) && !$this->nullable) {
            return $this->getDefault();
        }

        return $this->readValue($model);
    }

    /**
     * {@inheritDoc}
     */
    public function write($model, $value)
    {
        if (!$this->nullable || $value !== null) {
            $value = $this->toPHP($value);
        }

        return $this->writeValue($model, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize($model)
    {
        $value = $this->hasValue($model)
            ? $this->readValue($model)
            : $this->getDefault();

        // Pass nothing when nullable, and let the implementation
        // handle the appropriate null-ation of the key
        if ($value === null && $this->nullable) {
            return [];
        }

        return [$this->key => $this->toNative($value)];
    }


    /**
     * {@inheritDoc}
     */
    public function deserialize(array $result)
    {
        if (array_key_exists($this->key, $result)) {
            return [$this->name => $this->toPHP($result[$this->key])];
        }

        return [];
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
     * Should return the value casted to the native, internal type.
     *
     * @return  mixed
     */
    public function toNative($value)
    {
        return $this->cast($value);
    }

    /**
     * Should return the value casted to the PHP type.
     *
     * @return  mixed
     */
    public function toPHP($value)
    {
        return $this->cast($value);
    }

    /**
     * Should return the value casted to an appropriate type.
     *
     * @param  mixed  $value
     * @return mixed
     */
    abstract public function cast($value);
}
