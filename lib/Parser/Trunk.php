<?php namespace Armagetron\Parser;

use Armagetron\Player;
use Armagetron\Team;

class Trunk extends Common
{
    protected static function online_player($name, $ping, $team, $accessLevel)
    {
        //list($name, $ping, $team, $accessLevel) = func_get_args();
        $player = Player::get($name);

        $player->accessLevel = $accessLevel;
        $player->ping = $ping;
        $player->team = Team::get($team);
    }

    public static function __callStatic($function, $args)
    {
        call_user_func_array(array('Common', $function), $args);
    }
}