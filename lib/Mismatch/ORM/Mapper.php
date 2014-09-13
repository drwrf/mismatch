<?php

namespace Mismatch\ORM;

use Mismatch\Entity;
use Mismatch\ORM\Attr\Relationship;

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
     * Given a model, this should turn it into a saveable array of data.
     *
     * @param   Mismatch\Model  $model
     * @return  array
     */
    public function serialize($model)
    {
        // TODO
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
        foreach ($this->attrs as $attr) {
            // XXX: This might cause issues, maybe there's a need
            // for a no-value object to track this case.
            if (!isset($result[$attr->key])) {
                $result[$attr->key] = null;
            }

            $result[$attr->name] = $attr->deserialize($result, $result[$attr->key]);
        }

        return new $this->class($result);
    }
}
