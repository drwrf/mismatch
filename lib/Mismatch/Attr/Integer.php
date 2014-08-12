<?php

namespace Mismatch\Attr;

class Integer extends Primitive
{
    public $default = 0;

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (int) $value;
    }
}
