<?php

namespace Mismatch\Attr;

class Set extends Primitive
{
    public $default = [];

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (array) $value;
    }
}
