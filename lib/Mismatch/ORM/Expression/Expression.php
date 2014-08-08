<?php

namespace Mismatch\ORM\Expression;

class Expression implements ExpressionInterface
{
    /**
     * @var  mixed  $expr
     */
    protected $expr;

    /**
     * @var  mixed  $binds
     */
    protected $binds;

    /**
     * @param  string  $expr
     * @param  array   $binds
     */
    public function __construct($expr, array $binds)
    {
        $this->binds = $binds;
        $this->expr = $expr;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpr($column = null)
    {
        return sprintf($this->expr, $column);
    }

    /**
     * {@inheritDoc}
     */
    public function getBinds()
    {
        return $this->binds;
    }
}
