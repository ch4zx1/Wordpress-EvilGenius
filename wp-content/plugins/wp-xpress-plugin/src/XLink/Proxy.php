<?php

namespace XLink;

class Proxy implements \ArrayAccess {
    protected static $instance;

    public function __construct()
    {
        self::$instance = $this;
    }

    public function __call($name, $arguments)
    {
        return $this;
    }

    public static function __callStatic($name, $arguments)
    {
        return self::$instance;
    }

    public function __get($name)
    {
        return $this;
    }

    public function __set($name, $value)
    {
        return null;
    }

    public function __toString()
    {
        return '0';
    }

    public function offsetExists($offset)
    {
        return true;
    }

    public function offsetGet($offset)
    {
        return $this;
    }

    public function offsetSet($offset, $value)
    {
        return;
    }

    public function offsetUnset($offset)
    {
        return;
    }
}