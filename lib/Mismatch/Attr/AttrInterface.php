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
    public function read($model);

    /**
     * Should return the value as it should be stored internally.
     *
     * @param   Mismatch\Model  $model
     * @param   mixed           $value
     * @return  mixed
     */
    public function write($model, $value);
}
