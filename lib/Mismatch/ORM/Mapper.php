<?php

namespace Mismatch\ORM;

class Mapper
{
    /**
     * @var  string  $class
     */
    private $class;

    /**
     * @var  Mismatch\Attrs  $attrs
     */
    private $attrs;

    /**
     * Constructor.
     *
     * @param  string          $class
     * @param  Mismatch\Attrs  $attrs
     */
    public function __construct($class, $attrs)
    {
        $this->class = $class;
        $this->attrs = $attrs;
    }

    /**
     * Given a database result, this should map it to an
     * instance of a Mismatch model.
     *
     * @param  array  $result
     * @return Mismatch\Model
     */
    public function deserialize(array $result)
    {
        $data = [];

        foreach ($this->attrs as $attr) {
            $data = array_merge($data, $attr->deserialize($result));
        }

        return new $this->class($data);
    }
}
