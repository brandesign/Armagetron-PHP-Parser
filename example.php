#!/usr/bin/php
<?php namespace Armagetron;
require_once('bootstrap.php');

class Parser extends Parser\Main
{
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

        Command::comment('I am using '.Attribute::memoryUsage().' memory.');
    }
}

Parser::run();
