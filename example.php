#!/usr/bin/php
<?php namespace Armagetron;
require_once('bootstrap.php');

class Parser extends Parser\Main
{
    protected static function init()
    {
        // some settings
        Attribute::set('write_memory_usage', true);
    }

    protected static function player_entered($name, $ip, $screenName)
    {
        Player::get($name)->message('Welcome '.$screenName.'!');
    }

    protected static function new_round()
    {
        foreach(Player::getAll() as $player)
        {
            $player->message('You are online since '.$player->online_time().' seconds.');
        }

        if( Attribute::get('write_memory_usage') )
        {
            Command::comment('I am using '.Attribute::memoryUsage().' memory.');
        }
    }
}

Parser::run();
