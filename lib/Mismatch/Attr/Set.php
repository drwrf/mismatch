<?php

namespace Mismatch\Attr;

class Set extends Primitive
{
    protected $default = [];

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (array) $value;
    }
}
