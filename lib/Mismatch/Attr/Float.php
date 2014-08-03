<?php

namespace Mismatch\Attr;

class Float extends Primitive
{
    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (float) $value;
    }
}
