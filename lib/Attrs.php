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
        'Integer' => 'Mismatch\\Attr\\Integer',
        'Float'   => 'Mismatch\\Attr\\Float',
        'String'  => 'Mismatch\\Attr\\String',
        'Boolean' => 'Mismatch\\Attr\\Boolean',
        'Time'    => 'Mismatch\\Attr\\Time',
        'Set'     => 'Mismatch\\Attr\\Set',
    ];

    /**
     * Registers a type.
     *
     * @param  string  $name
     * @param  string  $class
     */
    public static function register($name, $class)
    {
        static::$types[$name] = $class;
    }

    /**
     * @var  array
     */
    private $attrs = [];

    /**
     * @var  string
     */
    private $pk = 'id';

    /**
     * @var  Mismatch\Metadata
     */
    private $metadata;

    /**
     * Constructor.
     *
     * @param  Mismatch\Metadata  $metadata
     */
    public function __construct($metadata)
    {
        $this->metadata = $metadata;
    }

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
            throw new UnknownAttrException($this, $name);
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
        ], $opts, [
            'metadata' => $this->metadata,
        ]);

        return $this->parseType($opts);
    }

    /**
     * @param   array $opts
     * @return  array
     */
    private function parseType(array $opts)
    {
        if (empty($opts['type'])) {
            throw new InvalidArgumentException();
        }

        $pattern = "/^(?<type>[\w\\\]+)(\[(?<each>[\w\\\]+)\])?(?<null>\?)?$/";

        if (false === preg_match($pattern, $opts['type'], $matches)) {
            throw new InvalidArgumentException();
        }

        // Resolve the type with the already declared types.
        $opts['type'] = $this->resolveType($matches['type']);

        // We can parse types that include sub-types, like "Foo[Bar]".
        // This is useful for types that return a list of types.
        if (!empty($matches['each'])) {
            $opts['each'] = $this->resolveType($matches['each']);
        }

        // Parse strings like "Foo" or "Foo?". A question mark at
        // the end of a string indicates the type is nullable.
        if (!empty($matches['null'])) {
            $opts['nullable'] = true;
        }

        return $opts;
    }

    /**
     * @param  string  $type
     * @return string
     */
    private function resolveType($type)
    {
        return isset(static::$types[$type]) ? static::$types[$type] : $type;
    }
}
