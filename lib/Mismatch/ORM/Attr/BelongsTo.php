<?php

namespace Mismatch\ORM\Attr;

use Mismatch\Inflector;

class BelongsTo extends Relationship
{
    /**
     * {@inheritDoc}
     */
    protected function isValid($value)
    {
        return $value instanceof $this->each;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadForeign($model)
    {
        $query = $this->foreignMeta()['query'];
        $value = $model->__get($this->ownerKey());

        // Use the foreign key only if it's declared. We can trust
        // the query class to use the right foreign key if not.
        if ($fk = $this->foreignKey()) {
            $value = [$fk => $value];
        }

        return $this->nullable
            ? $query->first($value)
            : $query->find($value);
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveOwnerKey()
    {
        if (is_array($this->key)) {
            return key($this->key);
        }

        if ($this->key !== $this->name) {
            return $this->key;
        }

        return $this->foreignMeta()['fk'];
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveForeignKey()
    {
        if (is_array($this->key)) {
            return current($this->key);
        }

        return $this->foreignMeta()['pk'];
    }
}
