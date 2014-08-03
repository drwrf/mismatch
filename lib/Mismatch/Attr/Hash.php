<?php

namespace Mismatch\Attr;

class Hash extends Primitive
{
    public function cast($value)
    {
        return (array) $value;
    }
}
