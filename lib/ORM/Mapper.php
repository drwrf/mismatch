<?php

namespace Mismatch\ORM;

use Mismatch\Entity;
use Mismatch\Metadata;
use Mismatch\Attr\AttrInterface;
use Mismatch\ORM\Attr\Primary;
use Mismatch\ORM\Attr\Relationship;
use UnexpectedValueException;

class Mapper
{
    /**
     * @var  string
     */
    private $class;

    /**
     * @var  Mismatch\Attrs
     */
    private $attrs;

    /**
     * @var  Mismatch\ORM\Query
     */
    private $query;

    /**
     * Constructor.
     *
     * @param  string              $class
     * @param  Mismatch\Attrs      $attrs
     * @param  Mismatch\ORM\Query  $query
     */
    public function __construct($class, $attrs)
    {
        $this->class = $class;
        $this->attrs = $attrs;
    }

    /**
     * Given a model this should save it and all its
     * relationships.
     *
     * @param   Mismatch\Model  $model
     * @return  array
     */
    public function save($model)
    {
        $conn = $this->conn();

        // Run this entire thing inside a transaction, so we
        // can roll it back in case of a failure.
        return $conn->transactional(function() use ($model) {
            $before = [];
            $after = [];
            $data = [];

            foreach ($this->attrs as $attr) {
                $name = $attr->name;
                $key = $attr->key;

                // Get a diff of the attribute. If there's no valuable
                // diff to return we'll simply skip the field.
                $diff = $model->diff($name);

                if (!$diff) {
                    continue;
                }

                // Run through the various serialization strategies
                switch ($attr->serialize) {
                    // We alway want to change the value, so place it on
                    // the main set of data to be saved.
                    case AttrInterface::SERIALIZE_VALUE;
                        $data[$key] = $attr->serialize($model, $diff);
                        break;

                    // We want to run a callback before the model is saved,
                    // so return a closure that we'll run inside the transaction.
                    case Transaction::SERIALIZE_BEFORE;
                        $before[] = $attr->serialize($model, $diff);
                        break;

                    // We want to run a callback after the model is saved,
                    // so return a closure that we'll run inside the transaction.
                    case AttrInterface::SERIALIZE_AFTER;
                        $after[] = $attr->serialize($model, $diff);
                        break;

                    case AttrInterface::SERIALIZE_NONE;
                        continue;

                    default:
                        throw new UnexpectedValueException();
                }
            }

            // Run through all of the pre-queries.
            foreach ($before as $fn) {
                if ($fn) {
                    $fn($this->query(), $model);
                }
            }

            // Run the main save.
            $query = $this->query();
            $query->set($data);
            $query->save($model->pk());

            // Run through all of the post-queries.
            foreach ($after as $fn) {
                if ($fn) {
                    $fn($this->query(), $model);
                }
            }
        });
    }

    /**
     * Given a database result, this should map it to an
     * instance of a Mismatch model.
     *
     * @param  array  $result
     * @return Mismatch\Model
     */
    public function prepare(array $result)
    {
        $entity = new Entity($result);

        foreach ($this->attrs as $attr) {
            // Here we allow attributes a chance to deserialize
            // values. This is useful for complex types that want
            // to perform custom type coercion on values.
            $entity->write($attr->name, $attr->deserialize(
                $entity, $entity->read($attr->key)));
        }

        // Since this is a result, we can count it as persisted.
        $entity->markAsPersisted();

        return new $this->class($entity);
    }

    /**
     * @return  Mismatch\DB\Connection
     */
    private function conn()
    {
        return Metadata::get($this->class)['conn'];
    }

    /**
     * @return  Mismatch\DB\Query
     */
    private function query()
    {
        return Metadata::get($this->class)['query'];
    }
}
