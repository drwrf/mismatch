<?php

namespace Mismatch\Attr;

use DateTime;
use DateTimeZone as TZ;

class Time extends Primitive
{
    protected $timezone = 'UTC';

    public function cast($value)
    {
        if (!($value instanceof DateTime)) {
            $value = new DateTime($value, new TZ($this->timezone));
        }

        return $value;
    }
}
