<?php

namespace Mismatch\ORM\Expression;

class Expression implements ExpressionInterface
{
    /**
     * @var  mixed  $expr
     */
    protected $expr;

    /**
     * @var  mixed  $values
     */
    protected $values;

    /**
     * @param  string  $expr
     * @param  array   $values
     */
    public function __construct($expr, array $values)
    {
        $this->values = $values;
        $this->expr = $expr;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpression($column = null)
    {
        return sprintf($this->expr, $column);
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return $this->values;
    }
}
