<?php namespace Armagetron;

class Command
{
    private $handle;
    private $EOL;
    private static $instance = null;

    private function __construct()
    {
        if( Config::get('output') == 'stdout' )
        {
            $this->handle = fopen('php://stdout', 'w');
        }
        else
        {
            $this->handle = fopen(Config::get('output'), 'a');
        }

        $this->EOL = Config::get('EOL') ? Config::get('EOL') : PHP_EOL;
    }

    public static function getInstance()
    {
        if( ! self::$instance )
        {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public static function write($s, $escape = true)
    {
        if( $escape )
        {
            $s = preg_replace('@\\\@', "\\\\", $s);
        }
        
        $instance = self::getInstance();
        fwrite($instance->handle, $s . $instance->EOL);
    }

    public static function comment($s)
    {
        self::write('# '.$s);
    }

    public static function say($s)
    {
        self::write('SAY '.$s);
    }

    public static function console_message($s)
    {
        self::write('CONSOLE_MESSAGE '.$s);
    }

    public static function center_message($s)
    {
        self::write('CENTER_MESSAGE '.$s);
    }

    public static function player_message(Player $player, $message)
    {
        self::write('PLAYER_MESSAGE '.$player->name.' "'.$message.'"', false);
    }

    public static function kill(Player $player)
    {
        self::write('KILL '.$player->name);
    }

    public static function kick(Player $player)
    {
        self::write('KICK '.$player->name);
    }

    public static function ban_player(Player $player, $minutes = 5)
    {
        self::write('BAN '.$player->name.' '.$minutes);
    }

    public static function ban_ip($ip, $minutes = 5)
    {
        self::write('BAN_IP '.$ip.' '.$minutes);
    }

    public static function suspend(Player $player, $rounds = null)
    {
        self::write('SUSPEND '.$player->name.' '.$rounds);
    }

    public static function unsuspend(Player $player)
    {
        self::write('UNSUSPEND '.$player->name.' '.$rounds);
    }

    private static function _include($file)
    {
        self::write('INCLUDE '.$file);
    }

    public static function sinclude($file)
    {
        self::write('SINCLUDE '.$file);
    }

    public static function __callStatic($function, $args)
    {
        if( $function == 'include' )
        {
            return call_user_func_array(array('self', '_include'), $args);
        }
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}
