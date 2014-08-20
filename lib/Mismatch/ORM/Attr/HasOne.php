<?php

namespace Mismatch\ORM\Attr;

use Mismatch\ORM\Query;
use Mismatch\Inflector;

class HasOne extends Relationship
{
    /**
     * {@inheritDoc}
     */
    protected function isValid($value)
    {
        return $value instanceof Query;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadForeign($model)
    {
        $query = $this->foreignMeta()['query'];

        // We don't actually load the model, simply start the WHERE
        // that will lead to a successful load of the model.
        return $query->limit(1)->where([
            $this->foreignKey() => $model->__get($this->ownerKey())
        ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveOwnerKey()
    {
        if (is_array($this->key)) {
            return key($this->key);
        }

        return $this->ownerMeta()['pk'];
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveForeignKey()
    {
        if (is_array($this->key)) {
            return current($this->key);
        }

        if ($this->key !== $this->name) {
            return $this->key;
        }

        return $this->ownerMeta()['fk'];
    }
}
