<?php

namespace Mismatch;

use Mismatch\Attr\AttrInterface;
use Mismatch\Exception\UnknownAttrException;

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
        $opts = $this->attrs[$name];

        // Allow passing a bare string for the type.
        // We can figure out the rest for the user.
        if (is_string($opts)) {
            $opts = [ 'type' => $opts ];
        }

        $opts = array_merge([
            'name' => $name,
            'key' => $name,
        ], $opts);

        $class = "Mismatch\\Attr\\{$opts['type']}";

        return new $class($opts);
    }
}
