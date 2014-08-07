<?php

namespace Mismatch\Attr;

use DateTime;
use DateTimeZone as TZ;

class Time extends Primitive
{
    protected $default = 'now';
    protected $timezone = 'UTC';

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
