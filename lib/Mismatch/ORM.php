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
        $m['connection'] = function ($m) {
            return ORM\Connector::connect($m['credentials']);
        };
    }
}
