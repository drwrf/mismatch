<?php

namespace Mismatch\Attr;

class Set extends Primitive
{
    public function cast($value)
    {
        return (array) $value;
    }
}
