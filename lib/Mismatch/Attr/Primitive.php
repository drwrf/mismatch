<?php

namespace Mismatch\Attr;

abstract class Primitive extends Base
{
    /**
     * {@inheritDoc}
     */
    public $serialize = AttrInterface::SERIALIZE_VALUE;

    /**
     * {@inheritDoc}
     */
    public function read($model, $value)
    {
        if ($value === null) {
            return $this->nullable ? null : $this->getDefault($model);
        }

        return $this->cast($value);
    }

    /**
     * {@inheritDoc}
     */
    public function write($model, $value)
    {
        if ($value === null && $this->nullable) {
            return null;
        }

        return $this->cast($value);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize($model, $diff)
    {
        // We got back either no change, or a change to null.
        if (!$diff || $diff[1] === null) {
            return $this->nullable ? null : $this->getDefault($model);
        }

        return $this->cast($diff[1]);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize($result, $value)
    {
        if ($value === null && $this->nullable) {
            return null;
        }

        return $this->cast($value);
    }

    /**
     * Should return the value casted to an appropriate type.
     *
     * @param  mixed  $value
     * @return mixed
     */
    abstract public function cast($value);

    /**
     * Should return the default value for the type.
     *
     * @return mixed
     */
    protected function getDefault($model)
    {
        return $this->default;
    }
}
