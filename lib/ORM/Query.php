<?php

namespace Mismatch\ORM;

use Mismatch\DB\Query as Base;

class Query extends Base
{
    /**
     * @var  Mapper
     */
    private $mapper;

    /**
     * Set the mapper to use for serializing and deserializing data.
     *
     * @param   Mapper  $mapper
     * @return  self
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareStatement($stmt, $strategy)
    {
        // Use the mapper to serialize results if one hasn't already been set.
        if (!$strategy && $this->mapper) {
            $strategy = [$this->mapper, 'prepare'];
        }

        return parent::prepareStatement($stmt, $strategy);
    }
}
