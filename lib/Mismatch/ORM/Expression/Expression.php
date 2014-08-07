<?php

namespace Mismatch\ORM\Expression;

class Expression
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
     * Returns the expression provided by the comparator.
     *
     * @param   string  $column
     * @return  string
     */
    public function getExpression($column)
    {
        return sprintf($this->expr, $column);
    }

    /**
     * @return  array
     */
    public function getValues()
    {
        return $this->values;
    }
}
