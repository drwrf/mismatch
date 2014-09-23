<?php

namespace Mismatch\Attr;

use DateTime;
use DateTimeZone as TZ;

class Time extends Primitive
{
    /**
     * @var  mixed  A valid Datetime::__construct argument
     */
    public $default = 'now';

    /**
     * @var  string  The format to use when writing to the native datasource
     */
    public $format = 'Y-m-d H:i:s';

    /**
     * @var   string  The timezone for the time.
     */
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
    protected function getDefault($model)
    {
        return new DateTime($this->default, new TZ($this->timezone));
    }
}
