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

    /**
     * Should extract the necessary keys from the result and
     * turn it into an array that the model can understand.
     *
     * @param   array  $result
     * @return  array
     */
    public function deserialize(array $result);
}
