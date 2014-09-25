<?php

namespace Armagetron\Event;

use Armagetron\Exception\GameObjectNotFoundException;
use Armagetron\GameObject\GameObjectCollection;
use Armagetron\GameObject\Player;
use Armagetron\GameObject\Team;

class Event
{
    protected $game_objects;

    public function __construct(array $definitions, GameObjectCollection $game_objects, array $arguments = array())
    {
        $this->game_objects = $game_objects;

        foreach( $definitions as $key => $definition )
        {
            $definition = trim($definition);

            if(array_key_exists($key, $arguments))
            {
                if( preg_match('@^(\w+):(\w+)$@', $definition, $matches) )
                {
                    $modifier       = 'parse'.ucfirst($matches[2]);
                    $definition     = $matches[1];
                    $command_arg    = $this->$modifier($arguments[$key]);
                }
                else
                {
                    $command_arg = $arguments[$key];
                }
            }
            else
            {
                $command_arg = null;
            }

            $this->{$definition} = $command_arg;
        }
    }

    public function getGameObjects()
    {
        return $this->game_objects;
    }

    public function parsePlayer($player)
    {
        $collection = $this->getGameObjects();

        try
        {
            $player = $collection->getPlayers()->getById($player);
        }
        catch( GameObjectNotFoundException $e )
        {
            $player = new Player($player);

            $collection->add($player);
        }

        return $player;
    }

    public function parseTeam($team)
    {
        $collection = $this->getGameObjects();

        try
        {
            $team = $collection->getTeams()->getById($team);
        }
        catch( GameObjectNotFoundException $e )
        {
            $team = new Team($team);

            $collection->add($team);
        }

        return $team;
    }

    public function parseZone($zone)
    {
        //return Zone::get($zone);
    }

    public function parsePlayerList($players)
    {
        $player_ids = explode(' ', $players);
        $players    = array();

        foreach($player_ids as $id)
        {
            $players[] = $this->parsePlayer($id);
        }

        return $players;
    }

    public static function parseInt($value)
    {
        return (int)$value;
    }

    public static function parseFloat($value)
    {
        return (float)$value;
    }

    public static function parseBool($value)
    {
        return (bool)$value;
    }

    public function __get($var)
    {
        return null;
    }
}