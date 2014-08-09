<?php

namespace Mismatch\Attr;

class Set extends Primitive
{
    protected $default = [];
    protected $each = null;

    /**
     * {@inheritDoc}
     */
    public function cast($value)
    {
        $value = (array) $value;

        if ($this->each) {
            foreach ($value as $key => $val) {
                $value[$key] = $this->castEach($val);
            }
        }

        return $value;
    }

    private function castEach($value)
    {
        if (is_string($this->each)) {
            $this->each = new $this->each();
        }

        return $this->each->cast($value);
    }
}
