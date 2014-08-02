<?php

namespace Mismatch\Attr;

interface AttrInterface
{
    /**
     * Should return the normalized value.
     *
     * @param   Mismatch\Model  $model
     * @return  mixed
     */
    public function getValue($model);

    /**
     * Should return the value as it should be stored internally.
     *
     * @param   Mismatch\Model  $model
     * @return  mixed
     */
    public function setValue($model, $value);
}
