<?php

namespace Mismatch;

use Mismatch\Attr\AttrInterface;
use Mismatch\Exception\UnknownAttrException;
use IteratorAggregate;
use ArrayIterator;

/**
 * Houses a set of Mismatch\Attr\AttrInterface's.
 *
 * This class mana
 */
class Attrs implements IteratorAggregate
{
    /**
     * @var  array
     */
    private $attrs;

    /**
     * @return  string
     */
    public function __toString()
    {
        return sprintf('%s:%s', get_class($this), json_encode(array_keys($this->attrs)));
    }

    /**
     * @param  string  $name
     * @param  mixed   $type
     */
    public function set($name, $type)
    {
        $this->attrs[$name] = $type;

        return $this;
    }

    /**
     * @param   string  $name
     * @return  Mismatch\Attr\AttrInterface
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new Exception\UnknownAttrException($this, $name);
        }

        if (!($this->attrs[$name] instanceof AttrInterface)) {
            $this->attrs[$name] = $this->buildAttr($name);
        }

        return $this->attrs[$name];
    }

    /**
     * @param   string  $name
     * @return  bool
     */
    public function has($name)
    {
        return isset($this->attrs[$name]);
    }

    /**
     * @return  ArrayIterator
     */
    public function getIterator()
    {
        $attrs = [];

        foreach ($this->attrs as $key => $val) {
            $attrs[$key] = $this->get($key);
        }

        return new ArrayIterator($attrs);
    }

    /**
     * @param  string  $name
     */
    private function buildAttr($name)
    {
        $opts = $this->parseOpts($name);
        $class = $opts['type'];

        return new $class($opts);
    }

    /**
     * @param  mixed  $name
     */
    private function parseOpts($name)
    {
        $opts = $this->attrs[$name];

        // Allow passing a bare string for the type.
        // We can figure out the rest for the user.
        if (is_string($opts)) {
            $opts = ['type' => $opts];
        }

        // Allow passing an array where the first value, regardless
        // of key, is the attribute type to use. This looks pretty.
        if (is_array($opts) && is_int(key($opts))) {
            $opts['type'] = $opts[key($opts)];
            unset($opts[key($opts)]);
        }

        $opts = array_merge([
            'name' => $name,
            'key' => $name,
        ], $opts);

        // Parses strings like "Foo" or "Foo?". A question mark at
        // the end of a string indicates the type is nullable.
        preg_match("/^([\w\\\]+)([?]?)$/", $opts['type'], $matches);

        if (!$matches[1]) {
            throw new \InvalidArgumentException();
        }

        $opts['type'] = $matches[1];
        $class = "Mismatch\\Attr\\{$opts['type']}";

        if (!is_subclass_of($class, 'Mismatch\Attr\AttrInterface')) {
            $opts['class'] = $opts['type'];
            $opts['type'] = 'Mismatch\Attr\Embedded';
        } else {
            $opts['type'] = $class;
        }

        if ($matches[2]) {
            $opts['nullable'] = true;
        }

        return $opts;
    }
}
