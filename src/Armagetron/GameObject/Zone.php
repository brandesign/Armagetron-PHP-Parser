<?php

namespace Armagetron\GameObject;

class Zone extends GameObject implements GameObjectInterface
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}