<?php

namespace Mismatch\Attr;

abstract class Base implements AttrInterface
{
    /**
     * The name of the attribute, which dictates the key that it is
     * retrieved and stored under.
     *
     * @var  string  $name
     */
    protected $name;

    /**
     * Whether or not the attribute is nullable. If it is true, then
     * "null"s written to the model will be written untouched.
     *
     * @param  bool  $name
     */
    protected $nullable = false;

    /**
     * A default value for the attribute.
     *
     * @param  mixed  $default
     */
    protected $default;

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
        return $model->entity->read($this->name);
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
        $model->entity->write($this->name, $value);

        return $this;
    }

    /**
     * Returns whether or not the model has a value for the attribute.
     *
     * @param   Mismatch\Model  $model
     * @return  bool
     */
    protected function hasValue($model)
    {
        return $model->entity->has($this->name);
    }
}
