<?php

namespace Mismatch\Attr;

class Integer extends Primitive
{
    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (int) $value;
    }
}
