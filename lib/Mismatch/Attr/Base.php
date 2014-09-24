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
    public $name;

    /**
     * The key of the attribute, which dictates the place that it is
     * stored and retrieved from externally.
     *
     * @var  string
     */
    public $key;

    /**
     * Whether or not the attribute is nullable. If it is true, then
     * "null"s written to the model will be written untouched.
     *
     * @param  bool  $name
     */
    public $nullable = false;

    /**
     * A default value for the attribute.
     *
     * @param  mixed  $default
     */
    public $default;

    /**
     * @var  int  The strategy to use for serialization.
     */
    public $serialize = AttrInterface::SERIALIZE_NONE;

    /**
     * The metadata of the model owning this attribute.
     *
     * @var  Mismatch\Metadata
     */
    public $metadata;

    /**
     * @param  array  $opts
     */
    public function __construct(array $opts = [])
    {
        foreach ($opts as $key => $value) {
            $this->$key = $value;
        }

        // While all attributes write to the entity using their
        // name, it is important for serialization purposes that
        // attributes know what key they're stored under.
        if (!isset($this->key)) {
            $this->key = $this->name;
        }
    }
}
