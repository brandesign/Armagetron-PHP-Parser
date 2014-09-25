<?php

namespace Armagetron\Parser;

use Armagetron\LadderLog\LadderLog;

class Trunk extends Parser implements ParserInterface
{
    public function deathExplosion($event)
    {
        $event->prey->deaths += 1;
        $event->hunter->kills += 1;
    }

    public function deathDeathzone($event)
    {
        $event->player->deaths += 1;
    }

    public function onlinePlayer( $event )
    {
        $player = $event->player;

        $player->accessLevel = $event->access_level;
        $player->ping = $event->ping;
        $player->team = $event->team;
    }

    public function setEventDefinitions()
    {
        parent::setEventDefinitions();

        LadderLog::getInstance()->getEventCollection()
            ->add('DEATH_DEATHZONE',        array('player:player') )
            ->add('DEATH_EXPLOSION',        array('prey:player', 'hunter:player') )
            ->add('MATCHES_LEFT',           array('number_matches:int') )
            ->add('NEW_WARMUP',             array('number_matches:int', 'time_string') )
            ->add('ONLINE_PLAYER',          array('player:player', 'ping:float', 'team:team', 'access_level:int') )
            ->add('PLAYER_RESPAWN',         array('player:player', 'team:team', 'respawner_team:team') )
            ->add('WINZONE_PLAYER_ENTER',   array('player:player') );
    }
}