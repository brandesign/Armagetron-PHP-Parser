<?php namespace Armagetron\Parser;

use Armagetron\Player;
use Armagetron\Team;
use Armagetron\Attribute;
use Armagetron\Event;

class StyCt extends Common
{
    protected function __construct()
    {
        parent::__construct();

        $this->registerEvent('ADMIN_COMMAND',           array('player:player', 'ip', 'access_level:int', 'command'))
            ->registerEvent('ADMIN_LOGIN',              array('player:player', 'ip'))
            ->registerEvent('ADMIN_LOGOUT',             array('player:player', 'ip'))
            ->registerEvent('BALL_VANISH',              array('goid', 'zone_name', 'x:float', 'y:float'))
            ->registerEvent('BASEZONE_CONQUERED',       array('team:team', 'x:float', 'y:float', 'enemies_in_zone:playerList'))
            ->registerEvent('BASEZONE_CONQUERER',       array('player:player', 'percent_won'))
            ->registerEvent('BASEZONE_CONQUERER_TEAM',  array('team:team', 'score'))
            ->registerEvent('BASE_ENEMY_RESPAWN',       array('respawner:player', 'player_respawned'))
            ->registerEvent('BASE_RESPAWN',             array('respawner:player', 'player_respawned'))
            ->registerEvent('COMMAND',                  array('command', 'player:player', 'ip', 'access_level:int', 'text'))
            ->registerEvent('CYCLE_CREATED',            array('player:player', 'x:float', 'y:float', 'x_dir:int', 'y_dir:int'))
            ->registerEvent('DEATH_BASEZONE_CONQUERED', array('player:player', 'enemies_in_zone:int'))
            ->registerEvent('DEATH_DEATHSHOT',          array('prey:player', 'hunter:player'))
            ->registerEvent('DEATH_DEATHZONE',          array('player:player'))
            ->registerEvent('DEATH_RUBBERZONE',         array('player:player'))
            ->registerEvent('DEATH_SELF_DESTRUCT',      array('prey:player', 'hunter:player'))
            ->registerEvent('DEATH_SHOT_FRAG',          array('prey:player', 'hunter:player'))
            ->registerEvent('DEATH_SHOT_SUICIDE',       array('player:player'))
            ->registerEvent('DEATH_SHOT_TEAMKILL',      array('prey:player', 'hunter:player'))
            ->registerEvent('DEATH_ZOMBIEZONE',         array('prey:player', 'hunter:player'))
            ->registerEvent('END_CHALLENGE',            array('time_string'))
            ->registerEvent('FLAG_CONQUEST_ROUND_WIN',  array('player:player', 'flag_team:team'))
            ->registerEvent('FLAG_DROP',                array('player:player', 'flag_team:team'))
            ->registerEvent('FLAG_HELD',                array('player:player'))
            ->registerEvent('FLAG_RETURN',              array('player:player'))
            ->registerEvent('FLAG_SCORE',               array('player:player', 'flag_team:team'))
            ->registerEvent('FLAG_TAKE',                array('player:player', 'flag_team:team'))
            ->registerEvent('FLAG_TIMEOUT',             array('player:player', 'flag_team:team'))
            ->registerEvent('INVALID_COMMAND',          array('command', 'player:player', 'ip', 'access_level:int', 'text'))
            ->registerEvent('MATCH_SCORE',              array('score:int', 'player:player', 'team:team'))
            ->registerEvent('MATCH_SCORE_TEAM',         array('score:int', 'team:team', 'sets_won:int'))
            ->registerEvent('NEW_SET',                  array('sets_played:int', 'time_string'))
            ->registerEvent('NEXT_ROUND',               array('round:int', 'limit_rounds:int', 'map', 'round_center_message'))
            ->registerEvent('ONLINE_PLAYER',            array('player:player', 'r:int', 'g:int', 'b:int', 'ping:float', 'team:team'))
            ->registerEvent('PLAYER_GRIDPOS',           array('player:player', 'x:float', 'y:float', 'x_dir:int', 'y_dir:int', 'team:team'))
            ->registerEvent('PLAYER_KILLED',            array('player:player', 'ip', 'x:float', 'y:float', 'x_dir:int', 'y_dir:int'))
            ->registerEvent('PLAYER_RENAMED',           array('player:player', 'new_name', 'ip', 'authenticated:bool', 'screen_name'))
            ->registerEvent('ROUND_COMMENCING',         array('round:int', 'limit_rounds:int'))
            ->registerEvent('SET_WINNER',               array('team:team'))
            ->registerEvent('SPAWN_POSITION_TEAM',      array('team:team', 'position:int'))
            ->registerEvent('START_CHALLENGE',          array('time_string'))
            ->registerEvent('SVG_CREATED')
            ->registerEvent('TACTICAL_POSITION',        array('time:float', 'player:player', 'tactical_position'))
            ->registerEvent('TACTICAL_STATISTICS',      array('tactical_position', 'player:player', 'time:float', 'state', 'kills:int'))
            ->registerEvent('TARGETZONE_CONQUERED',     array('goid:int', 'zone_name', 'x:float', 'y:float', 'player:player', 'team:team'))
            ->registerEvent('TARGETZONE_PLAYER_ENTER',  array(
                'goid:int',
                'zone_name', 'zone_x:float', 'zone_y:float',
                'player:player', 'player_x:float', 'player_y:float',
                'player_x_dir:int', 'player_y_dir:int',
                'time:float',
            ))
            ->registerEvent('TARGETZONE_PLAYER_LEFT',   array(
                'goid:int',
                'zone_name', 'zone_x:float', 'zone_y:float',
                'player:player', 'player_x:float', 'player_y:float',
                'player_x_dir:int', 'player_y_dir:int'
            ))
            ->registerEvent('TARGETZONE_TIMEOUT',       array('goid', 'zone_name', 'x:float', 'y:float'))
            ->registerEvent('VOTER',                    array('player:player', 'choice:bool', 'description'))
            ->registerEvent('VOTE_CREATED',             array('player:player', 'description'))
            ->registerEvent('WINZONE_PLAYER_ENTER',     array(
                'goid:int',
                'zone_name', 'zone_x:float', 'zone_y:float',
                'player:player', 'player_x:float', 'player_y:float', 'player_x_dir:int', 'player_y_dir:int',
                'time:float',
            ))
            ->registerEvent('ZONE_COLLAPSED',           array('zone_id:int', 'zone_name', 'zone_x:float', 'zone_y:float'))
            ->registerEvent('ZONE_SPAWNED',             array('goid:int', 'zone_name', 'x:float', 'y:float'))
            ->registerEvent('WAIT_FOR_EXTERNAL_SCRIPT');
    }
    
    protected function invalid_command($event)
    {
        $player = $event->player;
        
        $player->access_level = $event->access_level;
        
        CustomCommand::call($event->command, $event->text, $player);
    }

    protected function online_player($event)
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

    protected function player_gridpos($event)
    {
        $player = $event->player;

        $player->xpos = $event->x;
        $player->ypos = $event->y;
        $player->xdir = $event->x_dir;
        $player->ydir = $event->y_dir;
        $player->team = $event->team;
    }

    protected function player_position($event)
    {
        $event->player->position = $event->position;
    }

    protected function player_renamed($event)
    {
        $player = $event->player;
        
        $player->id = $event->new_name;
        $player->name = $event->new_name;
        $player->screen_name = $event->screen_name;
        $player->ip = $event->ip;
        $player->authenticated = (bool)$event->authenticated;
    }
}