<?php

namespace Mismatch\Attr;

class String extends Primitive
{
    public $default = '';

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
