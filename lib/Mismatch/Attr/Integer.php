<?php

namespace Mismatch\Attr;

class Integer extends Base
{
    public function read($model)
    {
        return $model->readValue($this->key);
    }

    public function write($model, $value)
    {
        return $model->writeValue($this->key, $value);
    }
}
