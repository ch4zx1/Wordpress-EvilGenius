<?php

/** @noinspection PhpUndefinedClassInspection */
class XPress implements \ArrayAccess
{
    function __call($name, $arguments)
    {
        return $this;
    }

    static function __callStatic($name, $arguments)
    {
        return new XPress();
    }

    function __get($name)
    {
        return $this;
    }

    function __set($name, $arguments)
    {

    }

    function __toString()
    {
        return '';
    }


    public function offsetExists($offset)
    {
        return false;
    }

    public function offsetGet($offset)
    {
        return $this;
    }

    public function offsetSet($offset, $value)
    {
        return null;
    }

    public function offsetUnset($offset)
    {
        return null;
    }
}