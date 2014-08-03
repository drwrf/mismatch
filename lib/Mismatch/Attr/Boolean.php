<?php

namespace Mismatch\Attr;

class Boolean extends Primitive
{
    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (bool) $value;
    }
}
