<?php

namespace Mismatch\Attr;

class Float extends Primitive
{
    public $default = 0.0;

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (float) $value;
    }
}
