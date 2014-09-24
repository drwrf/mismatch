<?php

namespace Mismatch\ORM;

use Closure;
use Exception;

class Transaction
{
    /**
     * @var  Mismatch\ORM\Query
     */
    private $query;

    /**
     * @var  array  A list of queries to run
     */
    private $callbacks = [];

    /**
     * @param  Mismatch\ORM\Query  $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Pushes a query on to the list of queries to be run.
     *
     * @param   Closure  $callback
     */
    public function push(Closure $callback)
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    /**
     * Commits the transaction.
     *
     * @return  void
     */
    public function commit()
    {
        $this->query->transactional(function () {
            foreach ($this->callbacks as $callback) {
                // Give each callback a new query instance that they
                // can work with. Better than having them manage that.
                $callback(clone $this->query);
            }
        });
    }
}
