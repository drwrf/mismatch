<?php

namespace Mismatch;

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
            return Inflector::tableize($m['name']);
        };

        // The primary key of the model, by name.
        $m['pk'] = function ($m) {
            return 'id';
        };

        // The connection the mode will use to talk to the database.
        $m['connection'] = $m->factory(function ($m) {
            return ORM\Connector::connect($m['credentials']);
        });

        // The query builder used for SELECTs
        $m['query'] = $m->factory(function($m) {
            return new ORM\Query($m['connection'], $m['pk']);
        });
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
}
