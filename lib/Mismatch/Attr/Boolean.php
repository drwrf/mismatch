<?php

namespace Mismatch\Attr;

class Boolean extends Primitive
{
    public function cast($value)
    {
        return (bool) $value;
    }
}
