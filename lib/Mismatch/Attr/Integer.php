<?php

namespace Mismatch\Attr;

class Integer extends Primitive
{
    protected $default = 0;

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (int) $value;
    }
}
