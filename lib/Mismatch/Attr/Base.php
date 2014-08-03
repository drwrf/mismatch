<?php

namespace Mismatch\Attr;

abstract class Base implements AttrInterface
{
    protected $name;
    protected $key;
    protected $type;
    protected $nullable = false;

    /**
     * @param  array  $opts
     */
    public function __construct(array $opts)
    {
        foreach ($opts as $key => $value) {
            $this->$key = $value;
        }
    }
}
