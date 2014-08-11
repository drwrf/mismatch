<?php

namespace Mismatch\ORM\Attr;

use Mismatch\Attr\Base;
use Mismatch\Inflector;
use Mismatch\Metadata;
use UnexpectedValueException;

class BelongsTo extends Base
{
    /**
     * @var  array  Tells us how the owner's keys map to the foreign table's keys.
     */
    private $mapping = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(array $opts = [])
    {
        parent::__construct($opts);

        // Passed nothing, so use the defaults.
        if (empty($this->key)) {
            $this->key = Inflector::tableize($this->name) . '_id';
        }

        // Passed a string, which is assumed to be the owner's key.
        // We can auto-detect the foreign_key based on its Metadata's "pk".
        if (is_string($this->key)) {
            $this->mapping = [
                'owner_key' => $this->key,
                'foreign_key' => null,
            ];
        }

        // Passed an ['owner_key' => 'foreign_key'], so trust both sides.
        if (is_array($this->key)) {
            $this->mapping = [
                'foreign_key' => current($this->key),
                'owner_key' => key($this->key),
            ];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function read($model)
    {
        $value = $this->readValue($model);

        // Allow nullable belongs to relationships. This really
        // doesn't work for other types of relationships.
        if ($this->nullable && $value === null) {
            return null;
        }

        if (!($value instanceof $this->each)) {
            $value = $this->loadValue($value);
            $this->writeValue($model, $value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function write($model, $value)
    {
        if (!($value instanceof $this->each) && !($this->nullable && $value === null)) {
            throw new UnexpectedValueException();
        }

        $this->writeValue($model, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize(array $result)
    {
        if (array_key_exists($this->ownerKey(), $result)) {
            return [$this->name => $result[$this->ownerKey()]];
        }

        return [];
    }

    /**
     * @param   mixed  $value
     * @return  Mismatch\Model
     */
    private function loadValue($value)
    {
        $query = $this->foreignMeta()['query'];

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
     * @return  string
     */
    private function ownerKey()
    {
        return $this->mapping['owner_key'];
    }

    /**
     * @return  string
     */
    private function foreignKey()
    {
        return $this->mapping['foreign_key'];
    }

    /**
     * @return  Mismatch\Metadata
     */
    private function foreignMeta()
    {
        return Metadata::get($this->each);
    }
}
