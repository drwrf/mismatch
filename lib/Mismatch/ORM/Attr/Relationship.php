<?php

namespace Mismatch\ORM\Attr;

use Mismatch\Attr\AttrInterface;
use Mismatch\Attr\Base;
use Mismatch\Metadata;
use UnexpectedValueException;

abstract class Relationship extends Base
{
    /**
     * {@inheritDoc}
     */
    public $serialize = AttrInterface::SERIALIZE_AFTER;

    /**
     * @var  string  The key on the owning side of the relationship.
     */
    protected $ownerKey;

    /**
     * @var  string  The key on the foreign side of the relationship.
     */
    protected $foreignKey;

    /**
     * {@inheritDoc}
     */
    public function read($model, $value)
    {
        if (!$this->isValid($value)) {
            $value = $this->loadForeign($model);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function write($model, $value)
    {
        if (!$this->isValid($value)) {
            throw new UnexpectedValueException();
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize($model, $diff)
    {
        return function () {};
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize($result, $value)
    {
        return; // TODO
    }

    /**
     * @return  Mismatch\Metadata
     */
    public function ownerMeta()
    {
        return $this->metadata;
    }

    /**
     * @return  Mismatch\Metadata
     */
    public function foreignMeta()
    {
        return Metadata::get($this->each);
    }

    /**
     * Returns the owner key of the relationship.
     *
     * @return  string
     */
    public function ownerKey()
    {
        if (!$this->ownerKey) {
            $this->ownerKey = $this->resolveOwnerKey();
        }

        return $this->ownerKey;
    }

    /**
     * Returns the foreign key of the relationship.
     *
     * @return  string
     */
    public function foreignKey()
    {
        if (!$this->foreignKey) {
            $this->foreignKey = $this->resolveForeignKey();
        }

        return $this->foreignKey;
    }

    /**
     * Hook called to determine whether or not the value
     * is a valid relationship.
     *
     * @param   mixed  $value
     * @return  mixed
     */
    abstract protected function isValid($value);

    /**
     * Hook called when no foreign model has been loaded yet.
     *
     * This should return a value that can be set on the owning
     * model and used by the caller.
     *
     * @param   Mismatch\Model  $model
     * @return  mixed
     */
    abstract protected function loadForeign($model);

    /**
     * Should attempt to figure out the owner key based on the
     * configuration passed to the attribute.
     *
     * @return  string
     */
    abstract protected function resolveOwnerKey();

    /**
     * Should attempt to figure out the foreign key based on the
     * configuration passed to the attribute.
     *
     * @return  string
     */
    abstract protected function resolveForeignKey();
}
