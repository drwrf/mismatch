<?php

namespace Mismatch;

trait Model
{
    /**
     * @var  Metadata
     */
    private static $metadata;

    /**
     * @param  Metadata
     */
    public static function usingModel($m)
    {
        static::$metadata = $m;
    }
}
