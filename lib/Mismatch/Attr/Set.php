<?php

namespace Mismatch\Attr;

class Set extends Primitive
{
    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (array) $value;
    }
}
