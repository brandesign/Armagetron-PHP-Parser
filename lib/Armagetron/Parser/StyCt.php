<?php namespace Armagetron\Parser;

use Armagetron\Player;
use Armagetron\Team;
use Armagetron\Attribute;

class StyCt extends Common
{
    protected static function online_player($name, $red, $green, $blue, $ping, $team)
    {
        $player = Player::get($name);

        $player->red = $red;
        $player->green = $green;
        $player->blue = $blue;
        $player->ping = $ping;
        $player->team = Team::get($team);
    }

    protected static function player_gridpos($name, $xpos, $ypos, $xdir, $ydir, $teamName)
    {
        $player = Player::get($name);
        $player->xpos = $xpos;
        $player->ypos = $ypos;
        $player->xdir = $xdir;
        $player->ydir = $ydir;
        $player->team = Team::get($teamName);
    }

    protected static function player_position($name, $ip, $position)
    {
        Player::get($name)->position = $position;
    }

    protected static function player_renamed($old, $new, $ip, $authed, $screenName)
    {
        $player = array(
            'name'  => $new,
            'ip'    => $ip,
            'screenName' => $screenName,
            'autenticated' => (bool)$authed,
        );

        Player::update($old, $new, $player);
    }

    public static function __callStatic($function, $args)
    {
        call_user_func_array(array('Common', $function), $args);
    }
}