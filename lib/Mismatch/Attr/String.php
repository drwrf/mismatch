<?php

namespace Mismatch\Attr;

class String extends Primitive
{
    public function cast($value)
    {
        return (string) $value;
    }
}
