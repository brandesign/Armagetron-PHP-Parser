<?php namespace Armagetron\Parser;

use Armagetron\Command;
use Armagetron\Player;
use Armagetron\Team;
use Armagetron\Attribute;

class Common extends Main
{
    protected function __construct()
    {
        $this->registerEvent('AUTHORITY_BLURB',     array('blurb', 'player:player', 'text') )
            ->registerEvent('BASEZONE_CONQUERED',   array('team:team', 'x:float', 'y:float') )
            ->registerEvent('BASEZONE_CONQUERER',   array('player:player') )
            ->registerEvent('CHAT',                 array('player:player', 'text') )
            ->registerEvent('COMMAND',              array('command', 'player:player', 'text') )
            ->registerEvent('DEATH_FRAG',           array('prey:player', 'hunter:player') )
            ->registerEvent('DEATH_SUICIDE',        array('player:player') )
            ->registerEvent('DEATH_TEAMKILL',       array('prey:player', 'hunter:player') )
            ->registerEvent('ENCODING',             array('charset') )
            ->registerEvent('GAME_END',             array('time_string') )
            ->registerEvent('GAME_TIME',            array('time:int') )
            ->registerEvent('MATCH_WINNER',         array('team:team', 'players:playerList' ) )
            ->registerEvent('NEW_MATCH',            array('time_string') )
            ->registerEvent('NEW_ROUND',            array('time_string') )
            ->registerEvent('NUM_HUMANS',           array('number_humans:int') )
            ->registerEvent('ONLINE_PLAYER',        array('player:player', 'ping:float', 'team:team') )
            ->registerEvent('PLAYER_ENTERED',       array('player:player', 'ip', 'screen_name') )
            ->registerEvent('PLAYER_LEFT',          array('player', 'ip') )
            ->registerEvent('PLAYER_RENAMED',       array('player:player', 'new_name', 'ip', 'screen_name') )
            ->registerEvent('POSITIONS',            array('team:team', 'players:playerList') )
            ->registerEvent('ROUND_SCORE',          array('score:int', 'player:player', 'team:team') )
            ->registerEvent('ROUND_SCORE_TEAM',     array('score:int', 'team:team') )
            ->registerEvent('ROUND_WINNER',         array('team:team', 'players:playerList') )
            ->registerEvent('SACRIFICE',            array('hole_user:player', 'hole_maker:player', 'wall_owner:player') )
            ->registerEvent('TEAM_CREATED',         array('team:team') )
            ->registerEvent('TEAM_DESTROYED',       array('team') )
            ->registerEvent('TEAM_PLAYER_ADDED',    array('team:team', 'player:player') )
            ->registerEvent('TEAM_PLAYER_REMOVED',  array('team:team', 'player:player') )
            ->registerEvent('TEAM_RENAMED',         array('team:team', 'new_name') )
            ->registerEvent('WAIT_FOR_EXTERNAL_SCRIPT');
    }

    protected function death_frag($event)
    {
        $event->prey->deaths += 1;
        $event->hunter->kills += 1;
    }

    protected function death_teamkill($event)
    {
        $event->prey->deaths += 1;
        $event->hunter->team_kills += 1;
    }

    protected function death_suicide($event)
    {
        $event->player->suicides += 1;
    }

    protected function command($event)
    {
        CustomCommand::call($event->command, $event->text, $event->player);
    }
    
    protected function encoding($event)
    {
        Attribute::set('encoding', $event->charset);
    }

    protected function game_time($event)
    {
        Attribute::set('game_time', $event->time);
    }

    protected function num_humans($event)
    {
        Attribute::set('number_humans', $event->number_humans);
    }

    protected function online_player($event)
    {
        $player = $event->player;

        $player->ping = $event->ping;
        $player->team = $event->team;
    }

    protected function player_entered($event)
    {
        $player = $event->player;

        $player->name       = $player->id;
        $player->ip         = $event->ip;
        $player->screen_name = $event->screen_name;
        $player->joined      = time();
        $player->is_human   = true;
    }

    protected function player_left($event)
    {
        Player::remove($event->player);
    }

    protected function player_renamed($event)
    {
        $player = $event->player;
        
        $player->id = $event->new_name;
        $player->name = $event->new_name;
        $player->screen_name = $event->screen_name;
        $player->ip = $event->ip;
    }

    protected function team_created($event)
    {
        //Team::add($event->team);
    }

    protected function team_destroyed($event)
    {
        Team::remove($event->team);
    }

    protected function team_player_added($event)
    {
        $event->team->addPlayer($event->player);
    }

    protected function team_player_removed($event)
    {
        $event->team->removePlayer($event->player);
    }

    protected function team_renamed($event)
    {
        $team = $event->team;
        
        $team->id = $event->id;
        $team->name = $event->new_name;
    }
}