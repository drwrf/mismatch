<?php

namespace Mismatch\ORM;

class Transaction
{
    /**
     * @var  array  A list of queries to run
     */
    private $queries = [];

    /**
     * Pushes a query on to the list of queries to be run.
     *
     * @param   Mismatch\ORM\Query  $query
     */
    public function push($query)
    {
        $this->queries[] = $query;
    }

    /**
     * Commits the transaction.
     */
    public function commit()
    {
        // TODO
    }
}
