<?php

namespace Armagetron\Server;

use Armagetron\LadderLog\LadderLog;
use Armagetron\GameObject\Player;
use Armagetron\GameObject\Team;
use React\Stream\Stream;

class Command
{
    protected $stream;
    static protected $instance;

    protected function __construct()
    {
        $this->stream = new Stream(fopen('php://stdout', 'w'), LadderLog::getInstance()->getLoop());
    }

    /**
     * @return static
     */
    static public function getInstance()
    {
        if( is_null(self::$instance) )
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function raw($s, $escape = true)
    {
        if( $escape )
        {
            $s = preg_replace('@\\\@', "\\\\", $s);
        }

        self::getInstance()->getStream()->write($s.PHP_EOL);
    }

    /**
     * @return Stream
     */
    public function getStream()
    {
        return $this->stream;
    }

    public static function say($s)
    {
        self::raw('SAY '.$s);
    }

    public static function consoleMessage($s)
    {
        self::raw('CONSOLE_MESSAGE '.$s);
    }

    public static function centerMessage($s)
    {
        self::raw('CENTER_MESSAGE '.$s);
    }

    public static function playerMessage(Player $player, $message)
    {
        self::raw('PLAYER_MESSAGE '.$player->getId().' "'.$message.'"', false);
    }

    public static function kill(Player $player)
    {
        self::raw('KILL '.$player->getId());
    }

    public static function kick(Player $player)
    {
        self::raw('KICK '.$player->getId());
    }

    public static function moveTo(Player $player, $host, $port)
    {
        self::raw('MOVE_TO '.$player->getId()." $host $port");
    }

    public static function kickTo(Player $player, $host, $port)
    {
        self::raw('KICK_TO '.$player->getId()." $host $port");
    }

    public static function banPlayer(Player $player, $minutes = 5)
    {
        self::raw('BAN '.$player->getId().' '.$minutes);
    }

    public static function banIp($ip, $minutes = 5)
    {
        self::raw('BAN_IP '.$ip.' '.$minutes);
    }

    public static function suspend(Player $player, $rounds = null)
    {
        self::raw('SUSPEND '.$player->getId().' '.$rounds);
    }

    public static function unsuspend(Player $player)
    {
        self::raw('UNSUSPEND '.$player->getId());
    }

    public static function sinclude($file)
    {
        self::raw('SINCLUDE '.$file);
    }

    public static function addScoreTeam(Team $team, $amount)
    {
        self::raw(sprintf("ADD_SCORE_TEAM %s %d", $team->getId(), $amount));
    }

    public static function respawnPlayer(Player $player, $x, $y, $dir_x, $dir_y, $show_message = true)
    {
        self::raw(sprintf("RESPAWN_PLAYER %s %d %s %s %d %d", $player->getId(), $show_message, $x, $y, $dir_x, $dir_y));
    }
}