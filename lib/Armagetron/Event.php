<?php namespace Armagetron;

class Event
{
    private static $events;

    public function __construct($command, $args, $parser)
    {
        $event_args = self::get($command, $parser);

        if( $event_args === false )
        {
            throw new \Exception('Unregistered event '.$command.' for '.$parser);
        }

        $command_args = explode(' ', $args, count($event_args));

        foreach( $event_args as $key => $event_arg )
        {
            if(array_key_exists($key, $command_args))
            {
                if( preg_match('@^(\w+):(\w+)$@', $event_arg, $matches) )
                {
                    $modifier = 'parse'.ucfirst($matches[2]);

                    $event_arg = $matches[1];
                    $command_arg = self::$modifier($command_args[$key]);
                }
                else
                {
                    $command_arg = $command_args[$key];
                }
            }
            else
            {
                $command_arg = null;
            }
            
            $this->{$event_arg} = $command_arg;
        }
    }

    public static function register($parser, $command, $args = array())
    {
        $command = strtolower($command);
        self::$events[$parser][$command] = $args;
    }

    public static function get($command, $parser)
    {
        if( array_key_exists($parser, self::$events) )
        {
            if( array_key_exists($command, self::$events[$parser]) )
            {
                return self::$events[$parser][$command];
            }
        }
        
        return false;
    }

    public static function getAll()
    {
        return self::$events;
    }

    public static function parsePlayer($player)
    {
        return Player::get($player);
    }

    public static function parseTeam($team)
    {
        return Team::get($team);
    }

    public static function parseZone($zone)
    {
        //return Zone::get($zone);
    }
    
    public static function parsePlayerList($players)
    {
        $player_ids = explode(' ', $players);
        $players = array();
        foreach($player_ids as $id)
        {
            $players[] = Player::get($id);
        }
        
        return $players;
    }
    
    public static function parseInt($value)
    {
        return (int)$value;
    }
    
    public static function parseFloat($value)
    {
        return (float)$value;
    }
    
    public static function parseBool($value)
    {
        return (bool)$value;
    }

    public function __get($var)
    {
        return null;
    }
}
