<?php

namespace Mismatch;

use Pimple;

class Metadata extends Pimple
{
    /**
     * @var  string  $class
     */
    private $class;

    /**
     * Constructor.
     *
     * @param   string  $class
     */
    public function __construct($class)
    {
        parent::__construct();

        $this->class = $class;
    }

    /**
     * Returns the FQCN that this metadata is for.
     *
     * @return  string
     */
    public function getClass()
    {
        return $this->class;
    }
}
