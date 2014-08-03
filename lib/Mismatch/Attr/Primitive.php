<?php

namespace Mismatch\Attr;

abstract class Primitive extends Base
{
    public function read($model)
    {
        $value = $model->readValue($this->key);

        if ($this->nullable && $value === null) {
            return null;
        }

        return $this->cast($value);
    }

    public function write($model, $value)
    {
        if (!$this->nullable || $value !== null) {
            $value = $this->cast($value);
        }

        return $model->writeValue($this->key, $value);
    }

    abstract public function cast($value);
}
