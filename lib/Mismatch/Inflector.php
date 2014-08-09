<?php

namespace Mismatch;

use Doctrine\Common\Inflector\Inflector as Base;

class Inflector extends Base
{
    /**
     * {@inheritDoc}
     */
    public static function tableize($word)
    {
        return str_replace('\\', '_', parent::tableize($word));
    }

    /**
     * Adds a source to a column if necessary.
     *
     * @param   string  $column
     * @param   string  $source
     * @return  string
     */
    public static function columnize($column, $source)
    {
        if ($source && !strpos($column, '.') && !strpos($column, '(')) {
            return $source. '.' . $column;
        } else {
            return $column;
        }
    }
}
