<?php

namespace Mismatch\Exception;

use OutOfBoundsException;

class UnknownAttrException extends OutOfBoundsException
{
    public function __construct($obj, $key)
    {
        parent::__construct(sprintf(
            'Invalid attribute "%s" on "%s"', $key, $obj));
    }
}
