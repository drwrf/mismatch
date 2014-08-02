<?php

namespace Mismatch\Attr;

class Integer extends Base
{
    public function getValue($model)
    {
        return $model->readValue($this->key);
    }

    public function setValue($model, $value)
    {
        return $model->writeValue($this->key, $value);
    }
}
