<?php

namespace Mismatch\Attr;

use DateTime;
use DateTimeZone as TZ;

class Time extends Primitive
{
    public $default = 'now';
    public $format = 'Y-m-d H:i:s';
    protected $timezone = 'UTC';

    /**
     * {@inheritDoc}
     */
    public function toNative($value)
    {
        return parent::toNative($value)->format($this->format);
    }


    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        if (!($value instanceof DateTime)) {
            $value = new DateTime($value, new TZ($this->timezone));
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefault()
    {
        return new DateTime($this->default, new TZ($this->timezone));
    }
}
