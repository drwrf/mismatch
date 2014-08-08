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
}
