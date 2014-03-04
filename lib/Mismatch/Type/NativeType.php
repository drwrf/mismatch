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
        switch ($type) {
            case 'int':
            case 'integer':
                $this->type = 'integer';
                break;

            case 'double':
            case 'float':
                $this->type = 'float';
                break;

            case 'bool':
            case 'boolean':
                $this->type = 'bool';
                break;

            case 'str':
            case 'string':
            case 'text':
                $this->type = 'string';
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
        return call_user_func('is_' . $this->type, $value);
    }
}
