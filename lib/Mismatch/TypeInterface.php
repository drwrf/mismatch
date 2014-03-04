<?php

namespace Mismatch;

interface TypeInterface
{
    /**
     * Returns whether or not the value passed is a valid type.
     *
     * This method will return true if the value is already of the
     * type the class expects.
     *
     * @param   mixed  $value
     * @param   array  $options
     * @return  bool
     */
    public function valid($value);
}
