<?php

namespace Mismatch\ORM\Expression;

class Any extends Composite
{
    /**
     * @param  mixed  $expr
     * @param  array  $binds
     */
    public function __construct($expr, array $binds = [])
    {
        $this->any($expr, $binds);
    }
}
