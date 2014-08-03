<?php

namespace Mismatch\Attr;

abstract class Primitive extends Base
{
    public function read($model)
    {
        return $this->cast($model->readValue($this->key));
    }

    public function write($model, $value)
    {
        return $model->writeValue($this->key, $this->cast($value));
    }

    abstract public function cast($value);
}
