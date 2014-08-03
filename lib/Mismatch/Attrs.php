<?php

namespace Mismatch;

use Mismatch\Attr\AttrInterface;
use Mismatch\Exception\UnknownAttrException;

/**
 * Houses a set of Mismatch\Attr\AttrInterface's.
 *
 * This class mana
 */
class Attrs
{
    /**
     * @var  array
     */
    private $attrs;

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
            throw new Exception\UnknownAttrException();
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
