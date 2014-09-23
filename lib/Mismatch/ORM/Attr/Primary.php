<?php

namespace Mismatch\ORM\Attr;

use Mismatch\Attr\Primitive;
use DomainException;

class Primary extends Primitive
{
    /**
     * {@inheritDoc}
     */
    public $each = 'Mismatch\Attr\Integer';

    /**
     * {@inheritDoc}
     */
    public $nullable = true;

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        return $this->each()->cast($value);
    }

    /**
     * @return  Mismatch\Attr\Primitive
     */
    protected function each()
    {
        if (is_string($this->each)) {
            $this->each = new $this->each();
        }

        return $this->each;
    }
}
