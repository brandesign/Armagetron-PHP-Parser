<?php

namespace Armagetron\GameObject;

use Armagetron\Server\Command;

class Team extends GameObject implements GameObjectInterface
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
        $this->players->delete($player->getId());
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function addScore($amount)
    {
        Command::addScoreTeam($this, $amount);
    }
}