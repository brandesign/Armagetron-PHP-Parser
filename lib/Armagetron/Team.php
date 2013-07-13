<?php namespace Armagetron;

class Team extends GameObject
{
    public $players = array();

    public function addPlayer(Player $player)
    {
        $this->players[] = $player;
        $player->team = $this;
    }

    public function removePlayer(Player $player)
    {
        foreach($this->players as $key => $p)
        {
            if($p == $player)
            {
                unset($this->players[$key]);
                $player->team = null;
            }
        }
    }
}
