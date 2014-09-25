<?php

namespace Armagetron\Parser;

use Armagetron\Event\Event;
use Armagetron\GameObject\Player;
use Armagetron\LadderLog\LadderLog;
use Armagetron\Toolkit;

class Parser implements ParserInterface
{
    public function __construct(ParserInterface $parser)
    {
        $this->setEventDefinitions();
        $this->registerParser($this);
        $this->registerParser($parser);
    }

    final public function run()
    {
        LadderLog::getInstance()->getLoop()->run();
    }

    final protected function registerParser(ParserInterface $parser)
    {
        /* @var LadderLog $ladder_log */
        $ladder_log = LadderLog::getInstance();
        $collection = $ladder_log->getEventCollection();
        $reflection = new \ReflectionClass($parser);

        foreach( $reflection->getMethods() as $method )
        {
            $original_name   = $method->getName();
            $underscore_name = Toolkit::camelCaseToUnderscore($original_name);

            if( $collection->hasEvent($underscore_name) )
            {
                $ladder_log->register($underscore_name, $parser, $original_name);
            }
        }
    }

    public function deathFrag(Event $event)
    {
        $event->prey->deaths += 1;
        $event->hunter->kills += 1;
    }

    public function deathTeamkill(Event $event)
    {
        $event->prey->deaths += 1;
        $event->hunter->team_kills += 1;
    }

    public function deathSuicide(Event $event)
    {
        $event->player->suicides += 1;
    }

    public function command(Event $event)
    {
        //CustomCommand::call($event->command, $event->text, $event->player);
    }

    public function encoding(Event $event)
    {
        LadderLog::getInstance()->setEncoding($event->charset);
    }

    public function gameTime(Event $event)
    {
        LadderLog::getInstance()->setGameTime($event->time);
    }

    public function onlinePlayer(Event $event)
    {
        $player = $event->player;

        $player->ping = $event->ping;
        $player->team = $event->team;
    }

    public function playerEntered(Event $event)
    {
        /* @var Player $player */
        $player = $event->player;

        $player->name       = $player->id;
        $player->ip         = $event->ip;
        $player->screen_name = $event->screen_name;
        $player->is_human   = true;
    }

    public function playerLeft(Event $event)
    {
        $event->getGameObjects()->remove($event->player->id);
    }

    public function playerRenamed(Event $event)
    {
        $player = $event->player;

        $player->id = $event->new_name;
        $player->name = $event->new_name;
        $player->screen_name = $event->screen_name;
        $player->ip = $event->ip;
    }

    public function teamDestroyed(Event $event)
    {
        $event->getGameObjects()->remove($event->team->id);
    }

    public function teamPlayerAdded(Event $event)
    {
        $event->team->addPlayer($event->player);
    }

    public function teamPlayerRemoved(Event $event)
    {
        $event->team->removePlayer($event->player);
    }

    public function teamRenamed(Event $event)
    {
        $team = $event->team;

        $team->id = $event->id;
        $team->name = $event->new_name;
    }

    public function setEventDefinitions()
    {
        LadderLog::getInstance()->getEventCollection()
            ->add('AUTHORITY_BLURB',             array('blurb', 'player:player', 'text'))
            ->add('BASEZONE_CONQUERED',          array('team:team', 'x:float', 'y:float'))
            ->add('BASEZONE_CONQUERER',          array('player:player'))
            ->add('CHAT',                        array('player:player', 'text'))
            ->add('COMMAND',                     array('command', 'player:player', 'text'))
            ->add('DEATH_FRAG',                  array('prey:player', 'hunter:player'))
            ->add('DEATH_SUICIDE',               array('player:player'))
            ->add('DEATH_TEAMKILL',              array('prey:player', 'hunter:player'))
            ->add('ENCODING',                    array('charset'))
            ->add('GAME_END',                    array('time_string'))
            ->add('GAME_TIME',                   array('time:int'))
            ->add('MATCH_WINNER',                array('team:team', 'players:playerList' ))
            ->add('NEW_MATCH',                   array('time_string'))
            ->add('NEW_ROUND',                   array('time_string'))
            ->add('NUM_HUMANS',                  array('number_humans:int'))
            ->add('ONLINE_PLAYER',               array('player:player', 'ping:float', 'team:team'))
            ->add('PLAYER_ENTERED',              array('player:player', 'ip', 'screen_name'))
            ->add('PLAYER_LEFT',                 array('player:player', 'ip'))
            ->add('PLAYER_RENAMED',              array('player:player', 'new_name', 'ip', 'screen_name'))
            ->add('POSITIONS',                   array('team:team', 'players:playerList'))
            ->add('ROUND_SCORE',                 array('score:int', 'player:player', 'team:team'))
            ->add('ROUND_SCORE_TEAM',            array('score:int', 'team:team'))
            ->add('ROUND_WINNER',                array('team:team', 'players:playerList'))
            ->add('SACRIFICE',                   array('hole_user:player', 'hole_maker:player', 'wall_owner:player'))
            ->add('TEAM_CREATED',                array('team:team'))
            ->add('TEAM_DESTROYED',              array('team:team'))
            ->add('TEAM_PLAYER_ADDED',           array('team:team', 'player:player'))
            ->add('TEAM_PLAYER_REMOVED',         array('team:team', 'player:player'))
            ->add('TEAM_RENAMED',                array('team:team', 'new_name'))
            ->add('WAIT_FOR_EXTERNAL_SCRIPT',    array());
    }
}