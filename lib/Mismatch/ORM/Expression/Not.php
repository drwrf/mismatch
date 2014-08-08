<?php

namespace Mismatch\ORM\Expression;

class Not extends Expression
{
    /**
     * @param  Expression  $value
     */
    public function __construct($expr)
    {
        if (!($expr instanceof Expression)) {
            $expr = is_array($expr) ? new In($expr) : new Eq($expr);
        }

        parent::__construct($this->expr, $expr->getBinds());
    }

    /**
     * {@inheritDoc}
     */
    public function getExpr($column = null)
    {
        return sprintf('NOT (%s)', $this->expr->getExpr($column));
    }
}
