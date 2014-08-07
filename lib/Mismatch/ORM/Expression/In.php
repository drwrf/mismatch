<?php

namespace Mismatch\ORM\Expression;

class In extends Expression
{
    public function __construct($value)
    {
        parent::__construct('%s IN ?', [$value]);
    }
}
