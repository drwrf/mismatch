<?php

namespace Mismatch\Attr;

class String extends Primitive
{
    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
