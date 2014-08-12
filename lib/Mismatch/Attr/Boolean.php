<?php

namespace Mismatch\Attr;

class Boolean extends Primitive
{
    public $default = false;

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (bool) $value;
    }
}
