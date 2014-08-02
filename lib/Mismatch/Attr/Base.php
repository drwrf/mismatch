<?php

namespace Mismatch\Attr;

abstract class Base implements AttrInterface
{
    protected $type;
    protected $name;
    protected $key;

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
