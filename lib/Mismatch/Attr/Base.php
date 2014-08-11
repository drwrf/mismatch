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
     * The key of the attribute, which dictates the place that it is
     * stored and retrieved from externally.
     *
     * @var  string
     */
    protected $key;

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
     * The metadata of the model owning this attribute.
     *
     * @var  Mismatch\Metadata
     */
    protected $metadata;

    /**
     * @param  array  $opts
     */
    public function __construct(array $opts = [])
    {
        foreach ($opts as $key => $value) {
            $this->$key = $value;
        }

        // Allow hooking into the options process.
        $this->initOpts();

        // While all attributes write to the entity using their
        // name, it is important for serialization purposes that
        // attributes know what key they're stored under.
        if (!isset($this->key)) {
            $this->key = $this->name;
        }
    }

    /**
     * Hook for setting options after construction.
     */
    protected function initOpts()
    {
        // Nothing to do by default.
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
