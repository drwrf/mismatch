<?php

namespace Mismatch\ORM;

use Doctrine\DBAL\Driver\Statement;
use InvalidArgumentException;
use Countable;
use Iterator;

class Result implements Iterator, Countable
{
    /**
     * @var  Statement
     */
    protected $stmt;

    /**
     * @var  string
     */
    protected $mode = 'array';

    /**
     * @var  int
     */
    protected $position = 0;

    /**
     * @var  array
     */
    protected $results = [];

    /**
     * @var  Mismatch\ORM\Mapper  The mapper to use for results
     */
    private $mapper;

    /**
     * @param  Statement
     */
    public function __construct(Statement $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Allows choosing a mode to fetch the data as.
     *
     * @param   string  $mode
     * @return  $this
     */
    public function fetchAs($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Set the mapper to use for turning databae results
     * into Mismatch models.
     *
     * @param   Mismatch\ORM\Mapper
     * @return  $this
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        if (isset($this->results[$this->position])) {
            return true;
        }

        $result = $this->stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $this->results[$this->position] = $result;
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        $result = $this->results[$this->position];

        if ($this->mode === 'array') {
            return $result;
        }

        if ($this->mode === 'mapped') {
            return $this->mapResult($result);
        }

        throw new InvalidArgumentException(sprintf('Invalid mode "%s".', $mode));
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return  int
     */
    public function count($mode = COUNT_NORMAL)
    {
        return $this->stmt->rowCount();
    }

    /**
     * @param   array  $result
     * @return  mixed
     */
    private function mapResult(array $result)
    {
        if (!$this->mapper) {
            throw new LogicException('Cannot map result if a mapper is not set.');
        }

        return $this->mapper->deserialize($result);
    }
}
