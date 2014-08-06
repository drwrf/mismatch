<?php

namespace Mismatch\Attr;

abstract class Base implements AttrInterface
{
    protected $name;
    protected $nullable = false;

    /**
     * @param  array  $opts
     */
    public function __construct(array $opts)
    {
        foreach ($opts as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Reads the attributes value from the model.
     *
     * @param  Mismatch\Model  $model
     * @return mixed
     */
    protected function readValue($model)
    {
        return $model->readValue($this->name);
    }

    /**
     * Writes the value to the model.
     *
     * @param   Mismatch\Model  $model
     * @param   mixed           $value
     * @return  $this
     */
    protected function writeValue($model, $value)
    {
        $model->writeValue($this->name, $value);

        return $this;
    }
}
