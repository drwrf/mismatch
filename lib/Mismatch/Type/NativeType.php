<?php

namespace Mismatch\Type;

use Mismatch\TypeInterface;
use InvalidArgumentException;

class NativeType implements TypeInterface
{
    /**
     * @var  string  The type of the class
     */
    private $type;

    /**
     * @var  callable  A function to use to validate the type
     */
    private $validator;

    /**
     * Constructor.
     *
     * @var  string  Any valid PHP type (or an alias for it)
     */
    public function __construct($type)
    {
        $this->type = $type;

        switch ($type) {
            case 'integer':
                $this->validator = 'is_integer';
                break;

            case 'float':
                $this->validator = 'is_float';
                break;

            case 'boolean':
                $this->validator = 'is_bool';
                break;

            case 'string':
                $this->validator = 'is_string';
                break;

            default:
                throw new InvalidArgumentException(sprintf(
                    'The type "%s" is not a native PHP type', $type));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function valid($value)
    {
        return call_user_func($this->validator, $value);
    }
}
