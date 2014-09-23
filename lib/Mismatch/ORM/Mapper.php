<?php

namespace Mismatch\ORM;

use Mismatch\Entity;
use Mismatch\Metadata;
use Mismatch\ORM\Attr\Primary;
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
        $tx = new Transaction();
        $related = [];
        $data = [];

        foreach ($this->attrs as $attr) {
            // Allow relationships to handle saving in their own special way
            if ($attr instanceof Relationship) {
                $related[] = $attr;
                continue;
            }

            $name = $attr->name;
            $key = $attr->key;

            // New records get all their special defaults and what-not
            // stored. While already persisted records only need changes.
            if ($model->isNew() || $model->changed($name)) {
                $data[$key] = $attr->serialize($model, $model->read($name));
            }
        }

        $query = $this->query()->set($data);

        // Perform the update first in line.
        $tx->push(function() use ($model, $query) {
            if (!$model->isNew()) {
                $query->update($model->id());
            } else {
                $query->insert();
            }
        });

        // Now run through all relationships and let them do their thang.
        foreach ($related as $attr) {
            $attr->serialize($model, $tx);
        }

        return $tx->commit();
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

    /**
     * @return  Mismatch\ORM\Query
     */
    private function query()
    {
        return Metadata::get($this->class)['query'];
    }
}
