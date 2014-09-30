<?php

namespace Mismatch;

use Mismatch\DB\Connection;
use Mismatch\ORM\Attr\Primary;
use DomainException;

trait ORM
{
    /**
     * Hook called when this is used on a Mismatch\Metadata-backed class.
     *
     * @param  Mismatch\Metadata  $m
     */
    public static function usingORM($m)
    {
        // The table that we want to connect the model to.
        $m['table'] = function($m) {
            return Inflector::tableize($m->getClass());
        };

        // The primary key of the model, as an attribute.
        $m['pk'] = function($m) {
            foreach ($m['attrs'] as $attr) {
                if ($attr instanceof Primary) {
                    return $attr;
                }
            }

            throw new DomainException();
        };

        // The default foreign key of the model, by name.
        $m['fk'] = function($m) {
            return $m['table'] . '_id';
        };

        // The connection the model will use to talk to the database.
        $m['conn'] = $m->factory(function ($m) {
            return Connection::create($m['credentials']);
        });

        // The query builder used for finding and modifying data
        $m['query'] = $m->factory(function($m) {
            $query = new $m['query:class']($m['conn'], $m['table'], $m['pk']);
            $query->setMapper($m['mapper']);

            return $query;
        });

        // The class to use for query building.
        $m['query:class'] = 'Mismatch\ORM\Query';

        // The mapper instance for serialization and deserialization.
        $m['mapper'] = function($m) {
            return new $m['mapper:class']($m->getClass(), $m['attrs']);
        };

        // The class to use for mapping data.
        $m['mapper:class'] = 'Mismatch\ORM\Mapper';
    }

    /**
     * Proxy to the query builder, so that all of its methods are
     * exposed as static methods on the class.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    public static function __callStatic($method, array $args)
    {
        $query = Metadata::get(get_called_class())['query'];

        return call_user_func_array([$query, $method], $args);
    }

    /**
     * Returns the primary key of the record.
     *
     * @return  mixed
     */
    public function pk()
    {
        return $this->__get(static::metadata()['pk']->name);
    }

    /**
     * Allows saving this particular model.
     *
     * @return  bool
     */
    public function save()
    {
        if ($this->isPersisted()) {
            return static::metadata()['mapper']->update($this);
        } else {
            return static::metadata()['mapper']->create($this);
        }
    }

    /**
     * Allows destroying this particular model.
     *
     * @return  bool
     */
    public function destroy()
    {
        return (bool) static::metadata()['mapper']->destroy($this);
    }
}

// Register the custom types we've got going on.
Attrs::register('Primary', 'Mismatch\ORM\Attr\Primary');
Attrs::register('BelongsTo', 'Mismatch\ORM\Attr\BelongsTo');
Attrs::register('HasMany', 'Mismatch\ORM\Attr\HasMany');
