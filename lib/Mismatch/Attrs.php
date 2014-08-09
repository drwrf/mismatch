<?php

namespace Mismatch;

use Mismatch\Attr\AttrInterface;
use Mismatch\Exception\UnknownAttrException;
use InvalidArgumentException;
use IteratorAggregate;
use ArrayIterator;

class Attrs implements IteratorAggregate
{
    /**
     * @var  array
     */
    private static $types = [
        'Integer' => ['type' => 'Mismatch\\Attr\\Integer'],
        'Float'   => ['type' => 'Mismatch\\Attr\\Float'],
        'String'  => ['type' => 'Mismatch\\Attr\\String'],
        'Boolean' => ['type' => 'Mismatch\\Attr\\Boolean'],
        'Time'    => ['type' => 'Mismatch\\Attr\\Time'],
        'Set'     => ['type' => 'Mismatch\\Attr\\Set'],
    ];

    /**
     * Registers a type.
     *
     * @param  string  $name
     * @param  array   $opts
     */
    public static function register($name, array $opts)
    {
        static::$types[$name] = $opts;
    }

    /**
     * @var  array
     */
    private $attrs = [];

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
        preg_match("/^(?<type>[\w\\\]+)(?<set>\[\])?(?<null>\?)?$/", $opts['type'], $matches);

        if (empty($matches['type'])) {
            throw new InvalidArgumentException();
        }

        $opts['type'] = $matches['type'];

        if (!empty($matches['set'])) {
            $opts['type'] = 'Set';
            $opts['each'] = $matches['type'];
        }

        if (!empty($matches['null'])) {
            $opts['nullable'] = true;
        }

        // Resolve the type with the already declared types
        if (!empty(static::$types[$opts['type']])) {
            $opts = array_merge($opts, static::$types[$opts['type']]);
        }

        // Also resolve the "each" type, in the case of sets.
        if (isset($opts['each']) && isset(static::$types[$opts['each']])) {
            $opts['each'] = static::$types[$opts['each']]['type'];
        }

        return $opts;
    }
}
