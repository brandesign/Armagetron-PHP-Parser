<?php

namespace Armagetron\GameObject;

class Team implements GameObjectInterface
{
    public $id;
    protected $players;

    public function __construct($id)
    {
        $this->id = $id;
        $this->players = new GameObjectCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addPlayer(Player $player)
    {
        $this->players->add($player);
    }

    public function removePlayer(Player $player)
    {
        $this->players->remove($player->getId());
    }

    public function getPlayers()
    {
        return $this->players;
    }
}