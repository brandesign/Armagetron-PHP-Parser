<?php

namespace Armagetron\GameObject;

abstract class GameObject
{
    protected $properties = array();

    public function setProperty($key, $value)
    {
        $this->properties[$key] = $value;
    }

    public function getProperty($key, $default = null)
    {
        return isset($this->properties[$key]) ? $this->properties[$key] : $default;
    }
}