<?php namespace Armagetron\Parser;

use Armagetron\Player;
use Armagetron\Team;

class Trunk extends Common
{
    protected function __construct()
    {
        $this->registerEvent('DEATH_DEATHZONE', array('player:player') )
            ->registerEvent('DEATH_EXPLOSION', array('prey:player', 'hunter:player') )
            ->registerEvent('MATCHES_LEFT', array('number_matches:int') )
            ->registerEvent('NEW_WARMUP', array('number_matches:int', 'time_string') )
            ->registerEvent('ONLINE_PLAYER', array('player:player', 'ping:float', 'team:team', 'access_level:int') )
            ->registerEvent('PLAYER_RESPAWN', array('player:player', 'team:team', 'respawner_team:team') )
            ->registerEvent('WINZONE_PLAYER_ENTER', array('player:player') );
    }

    protected function death_explosion($event)
    {
        $event->prey->deaths += 1;
        $event->hunter->kills += 1;
    }

    protected function death_deathzone($event)
    {
        $event->player->deaths += 1;
    }

    protected function online_player( $event )
    {
        $player = $event->player;

        $player->accessLevel = $event->access_level;
        $player->ping = $event->ping;
        $player->team = $event->team;
    }
}