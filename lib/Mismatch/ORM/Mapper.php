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
     * Given a model, this should turn it into a saveable array of data.
     *
     * @param   Mismatch\Model  $model
     * @return  array
     */
    public function serialize($model)
    {
        $query = $this->query();

        // Run this entire thing inside a transaction, so we
        // can roll it back in case of a failure.
        return $query->transactional(function() use ($model) {
            $before = [];
            $after = [];
            $data = [];

            foreach ($this->attrs as $attr) {
                $name = $attr->name;
                $key = $attr->key;

                // Only serialize changed values or values on new models.
                // There's no need to make any changes otherwise.
                if (!$model->isNew() && !$model->changed($name)) {
                    continue;
                }

                $diff = $model->diff($name);

                // Run through the various serialization strategies
                switch ($attr->serialize) {
                    // We alway want to change the value, so place it on
                    // the main set of data to be saved.
                    case AttrInterface::SERIALIZE_VALUE;
                        $data[$key] = $attr->serialize($model, $diff);
                        break;

                    // We want to run a callback before the model is saved,
                    // so return a closure that we'll run inside the transaction.
                    case AttrInterface::SERIALIZE_BEFORE;
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

            if (!$model->isNew()) {
                $query->update($model->pk());
            } else {
                $model->setPk($query->insert());
            }

            // Run through all of the post-queries.
            foreach ($after as $fn) {
                if ($fn) {
                    $fn($this->query(), $model);
                }
            }

            // TODO Mark the model as saved
        });
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
