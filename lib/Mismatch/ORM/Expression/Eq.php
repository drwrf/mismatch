<?php

namespace Mismatch\ORM\Expression;

class Eq extends Expression
{
    public function __construct($value)
    {
        parent::__construct('%s = ?', [$value]);
    }
}
