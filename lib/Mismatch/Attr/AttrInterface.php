<?php

namespace Mismatch\Attr;

interface AttrInterface
{
    const SERIALIZE_NONE = 0;
    const SERIALIZE_VALUE = 1;
    const SERIALIZE_BEFORE = 2;
    const SERIALIZE_AFTER = 3;

    /**
     * Called when writing a value to the model in PHP land.
     *
     * @param   Mismatch\Model  $model
     * @param   mixed           $value
     * @return  mixed
     */
    public function write($model, $value);

    /**
     * Called when reading a value from a model.
     *
     * @param   Mismatch\Model  $model
     * @param   mixed           $value
     * @return  mixed
     */
    public function read($model, $value);

    /**
     * Called when reading a value from the datasource and it needs
     * to be turned into a native PHP type.
     *
     * @param   mixed  $result
     * @param   mixed  $value
     * @return  mixed
     */
    public function deserialize($result, $value);

    /**
     * Called when reading a value from a model that needs to
     * be turned into a native type for the datasource.
     *
     * @param   Mismatch\Model  $model
     * @param   array|false     $diff
     * @return  mixed
     */
    public function serialize($model, $diff);
}
