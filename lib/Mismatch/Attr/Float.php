<?php

namespace Mismatch\Attr;

class Float extends Primitive
{
    public function cast($value)
    {
        return (float) $value;
    }
}
