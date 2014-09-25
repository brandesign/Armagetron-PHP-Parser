<?php

namespace Armagetron\Parser;

use Armagetron\Event\Event;
use Armagetron\LadderLog\LadderLog;

class StyCt extends Parser implements ParserInterface
{
    public function deathDeathshot(Event $event)
    {
        $event->prey->deaths += 1;
        $event->hunter->kills += 1;
    }

    public function deathDeathzone(Event $event)
    {
        $event->player->deaths += 1;
    }

    public function deathRubberzone(Event $event)
    {
        $event->player->deaths += 1;
    }

    public function deathSelfDestruct(Event $event)
    {
        $event->player->suicides += 1;
    }

    public function deathShotFrag(Event $event)
    {
        $event->prey->deaths += 1;
        $event->hunter->kills += 1;
    }

    public function deathShotSuicide(Event $event)
    {
        $event->player->suicides += 1;
    }

    public function deathShotTeamkill(Event $event)
    {
        $event->prey->deaths += 1;
        $event->hunter->team_kills += 1;
    }

    public function deathZombiezone(Event $event)
    {
        $event->prey->deaths += 1;
        $event->hunter->kills += 1;
    }

    public function command(Event $event)
    {
        $player = $event->player;

        $player->access_level = $event->access_level;
    }

    public function invalidCommand(Event $event)
    {
        $this->command($event);
    }

    public function onlinePlayer(Event $event)
    {
        $player = $event->player;

        $player->red = $event->r;
        $player->green = $event->g;
        $player->blue = $event->b;
        $player->ping = $event->ping;

        if( $event->team )
        {
            $player->team = $event->team;
        }
    }

    public function playerGridpos(Event $event)
    {
        $player = $event->player;

        $player->xpos = $event->x;
        $player->ypos = $event->y;
        $player->xdir = $event->x_dir;
        $player->ydir = $event->y_dir;
        $player->team = $event->team;
    }

    public function playerPosition(Event $event)
    {
        $event->player->position = $event->position;
    }

    public function playerRenamed(Event $event)
    {
        $player = $event->player;

        $player->id = $event->new_name;
        $player->name = $event->new_name;
        $player->screen_name = $event->screen_name;
        $player->ip = $event->ip;
        $player->authenticated = (bool)$event->authenticated;
    }

    public function setEventDefinitions()
    {
        parent::setEventDefinitions();

        LadderLog::getInstance()->getEventCollection()
            ->add('ADMIN_COMMAND',            array('player:player', 'ip', 'access_level:int', 'command'))
            ->add('ADMIN_LOGIN',              array('player:player', 'ip'))
            ->add('ADMIN_LOGOUT',             array('player:player', 'ip'))
            ->add('BALL_VANISH',              array('goid', 'zone_name', 'x:float', 'y:float'))
            ->add('BASEZONE_CONQUERED',       array('team:team', 'x:float', 'y:float', 'enemies_in_zone:playerList'))
            ->add('BASEZONE_CONQUERER',       array('player:player', 'percent_won'))
            ->add('BASEZONE_CONQUERER_TEAM',  array('team:team', 'score'))
            ->add('BASE_ENEMY_RESPAWN',       array('respawner:player', 'player_respawned'))
            ->add('BASE_RESPAWN',             array('respawner:player', 'player_respawned'))
            ->add('COMMAND',                  array('command', 'player:player', 'ip', 'access_level:int', 'text'))
            ->add('CYCLE_CREATED',            array('player:player', 'x:float', 'y:float', 'x_dir:int', 'y_dir:int'))
            ->add('DEATH_BASEZONE_CONQUERED', array('player:player', 'enemies_in_zone:int'))
            ->add('DEATH_DEATHSHOT',          array('prey:player', 'hunter:player'))
            ->add('DEATH_DEATHZONE',          array('player:player'))
            ->add('DEATH_RUBBERZONE',         array('player:player'))
            ->add('DEATH_SELF_DESTRUCT',      array('prey:player', 'hunter:player'))
            ->add('DEATH_SHOT_FRAG',          array('prey:player', 'hunter:player'))
            ->add('DEATH_SHOT_SUICIDE',       array('player:player'))
            ->add('DEATH_SHOT_TEAMKILL',      array('prey:player', 'hunter:player'))
            ->add('DEATH_ZOMBIEZONE',         array('prey:player', 'hunter:player'))
            ->add('END_CHALLENGE',            array('time_string'))
            ->add('FLAG_CONQUEST_ROUND_WIN',  array('player:player', 'flag_team:team'))
            ->add('FLAG_DROP',                array('player:player', 'flag_team:team'))
            ->add('FLAG_HELD',                array('player:player'))
            ->add('FLAG_RETURN',              array('player:player'))
            ->add('FLAG_SCORE',               array('player:player', 'flag_team:team'))
            ->add('FLAG_TAKE',                array('player:player', 'flag_team:team'))
            ->add('FLAG_TIMEOUT',             array('player:player', 'flag_team:team'))
            ->add('INVALID_COMMAND',          array('command', 'player:player', 'ip', 'access_level:int', 'text'))
            ->add('MATCH_SCORE',              array('score:int', 'player:player', 'team:team'))
            ->add('MATCH_SCORE_TEAM',         array('score:int', 'team:team', 'sets_won:int'))
            ->add('NEW_SET',                  array('sets_played:int', 'time_string'))
            ->add('NEXT_ROUND',               array('round:int', 'limit_rounds:int', 'map', 'round_center_message'))
            ->add('ONLINE_PLAYER',            array('player:player', 'r:int', 'g:int', 'b:int', 'ping:float', 'team:team'))
            ->add('PLAYER_GRIDPOS',           array('player:player', 'x:float', 'y:float', 'x_dir:int', 'y_dir:int', 'team:team'))
            ->add('PLAYER_KILLED',            array('player:player', 'ip', 'x:float', 'y:float', 'x_dir:int', 'y_dir:int'))
            ->add('PLAYER_RENAMED',           array('player:player', 'new_name', 'ip', 'authenticated:bool', 'screen_name'))
            ->add('ROUND_COMMENCING',         array('round:int', 'limit_rounds:int'))
            ->add('SET_WINNER',               array('team:team'))
            ->add('SPAWN_POSITION_TEAM',      array('team:team', 'position:int'))
            ->add('START_CHALLENGE',          array('time_string'))
            ->add('SVG_CREATED')
            ->add('TACTICAL_POSITION',        array('time:float', 'player:player', 'tactical_position'))
            ->add('TACTICAL_STATISTICS',      array('tactical_position', 'player:player', 'time:float', 'state', 'kills:int'))
            ->add('TARGETZONE_CONQUERED',     array('goid:int', 'zone_name', 'x:float', 'y:float', 'player:player', 'team:team'))
            ->add('TARGETZONE_PLAYER_ENTER',  array(
                'goid:int',
                'zone_name', 'zone_x:float', 'zone_y:float',
                'player:player', 'player_x:float', 'player_y:float',
                'player_x_dir:int', 'player_y_dir:int',
                'time:float',
            ))
            ->add('TARGETZONE_PLAYER_LEFT',   array(
                'goid:int',
                'zone_name', 'zone_x:float', 'zone_y:float',
                'player:player', 'player_x:float', 'player_y:float',
                'player_x_dir:int', 'player_y_dir:int'
            ))
            ->add('TARGETZONE_TIMEOUT',       array('goid', 'zone_name', 'x:float', 'y:float'))
            ->add('VOTER',                    array('player:player', 'choice:bool', 'description'))
            ->add('VOTE_CREATED',             array('player:player', 'description'))
            ->add('WINZONE_PLAYER_ENTER',     array(
                'goid:int',
                'zone_name', 'zone_x:float', 'zone_y:float',
                'player:player', 'player_x:float', 'player_y:float', 'player_x_dir:int', 'player_y_dir:int',
                'time:float',
            ))
            ->add('ZONE_COLLAPSED',           array('zone_id:int', 'zone_name', 'zone_x:float', 'zone_y:float'))
            ->add('ZONE_SPAWNED',             array('goid:int', 'zone_name', 'x:float', 'y:float'))
            ->add('WAIT_FOR_EXTERNAL_SCRIPT');
    }
}