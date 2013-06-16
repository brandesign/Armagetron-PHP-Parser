<?php namespace Armagetron\Parser;

use Armagetron\Command;
use Armagetron\Player;
use Armagetron\Team;
use Armagetron\Attribute;

class Common extends Main
{
    protected static function encoding($encoding)
    {
        Attribute::set('encoding', $encoding);
    }

    protected static function game_time($time)
    {
        Attribute::set('game_time', $time);
    }

    protected static function num_humans($num)
    {
        Attribute::set('human_players', $num);
    }

    protected static function online_player($name, $ping, $team)
    {
        $player = Player::get($name);

        if( ! $player )
        {
            $player = Player::add($name);
        }

        $player->ping = $ping;
        $player->team = Team::get($team);
    }

    protected static function player_entered($name, $ip, $screenName)
    {
        $player = array(
            'name'  => $name,
            'ip'    => $ip,
            'screenName' => $screenName,
            'joined'    => time(),
            'is_human'  => true,
        );
        Player::add($name, $player);
    }

    protected static function player_left($name, $ip)
    {
        Player::remove($name);
    }

    protected static function player_renamed($old, $new, $ip, $screenName)
    {
        $player = array(
            'name'  => $new,
            'ip'    => $ip,
            'screenName' => $screenName,
        );

        Player::update($old, $new, $player);
    }

    protected static function team_created($team)
    {
        Team::add($team);
    }

    protected static function team_destroyed($team)
    {
        Team::remove($team);
    }

    protected static function team_player_added($team, $player)
    {
        Team::get($team)->addPlayer(Player::get($player));
        /*
        $team = Team::get($team);
        $player = Player::get($player);

        $team->addPlayer($player);
        */
    }

    protected static function team_player_removed($team, $player)
    {
        Team::get($team)->removePlayer(Player::get($player));
    }

    protected static function team_renamed($old, $new)
    {
        Team::update($old, $new);
    }

    public static function __callStatic($function, $args)
    {
        return;
    }
}