<?php

namespace Mismatch;

use Mismatch\ORM\Attr\Primary;

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

        // The primary key of the model, by name.
        $m['pk'] = function ($m) {
            foreach ($m['attrs'] as $attr) {
                if ($attr instanceof Primary) {
                    return $attr->name;
                }
            }

            throw new DomainException();
        };

        // The default foreign key of the model, by name.
        $m['fk'] = function($m) {
            return $m['table'] . '_id';
        };

        // The connection the model will use to talk to the database.
        $m['connection'] = $m->factory(function ($m) {
            return ORM\Connector::connect($m['credentials']);
        });

        // The query builder used for SELECTs
        $m['query'] = $m->factory(function($m) {
            $query = new $m['query:class']($m['connection'], $m['pk']);
            $query->from([$m['table'] => $m['name']]);
            $query->setMapper($m['mapper']);

            return $query;
        });

        // The class to use for query building.
        $m['query:class'] = 'Mismatch\ORM\Query';

        // The mapper used to serialize and deserialize records.
        $m['mapper'] = function($m) {
            return new $m['mapper:class']($m->getClass(), $m['attrs']);
        };

        // The class to use for mapping results.
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
     * @return  bool
     */
    public function isNew()
    {
        return !$this->id();
    }

    /**
     * Returns the id of the record.
     *
     * @return  mixed
     */
    public function id()
    {
        return $this->__get(static::metadata()['pk']);
    }

    /**
     * Allows saving this particular model.
     *
     * @return  bool
     */
    public function save()
    {
        return static::metadata()['mapper']->serialize($this);
    }

    /**
     * Allows destroying this particular model.
     *
     * @return  bool
     */
    public function destroy()
    {
        return (bool) static::metadata()['query']->delete($this->id());
    }
}

// Register the custom types we've got going on.
Attrs::register('Primary', 'Mismatch\ORM\Attr\Primary');
Attrs::register('BelongsTo', 'Mismatch\ORM\Attr\BelongsTo');
Attrs::register('HasMany', 'Mismatch\ORM\Attr\HasMany');
