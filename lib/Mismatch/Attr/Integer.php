<?php

namespace Mismatch\Attr;

class Integer extends Primitive
{
    public function cast($value)
    {
        return (int) $value;
    }
}
