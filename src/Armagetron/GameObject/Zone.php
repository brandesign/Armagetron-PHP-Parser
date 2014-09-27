<?php

namespace Armagetron\GameObject;

class Zone extends GameObject implements GameObjectInterface
{
    public $id;
    public $name    = null;
    public $x       = null;
    public $y       = null;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}