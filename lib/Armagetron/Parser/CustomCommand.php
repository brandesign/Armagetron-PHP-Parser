<?php namespace Armagetron\Parser;

use Armagetron\Player;

class CustomCommand
{
    protected static $handlers = array();
    protected $access_levels = array();
    
    public static function registerHandler($handler)
    {
        if( ! is_object($handler) )
        {
            throw new \Exception('Custom command handler must be an object. '.gettype($handler).' given.');
        }
        
        self::$handlers[] = $handler;
    }
    
    public static function call($command, $args, Player $player)
    {
        $command = preg_replace('@^/@', '', strtolower($command));
        $command_is_valid = false;

        foreach( self::$handlers as $handler )
        {
            $reflector = new \ReflectionClass($handler);

            if( $reflector->hasMethod($command) )
            {
                $handler->execute($command, $args, $player);
                $command_is_valid = true;
            }
        }
        
        if( ! $command_is_valid )
        {
            $player->message('Invalid command '.$command);
        }
    }
    
    protected function execute($command, $args, Player $player)
    {
        if( $this->getAccessLevel($command) < $player->access_level )
        {
            $player->message('Your access level is not high enough to call '.$command);
            return;
        }
        
        return $this->$command($args, $player);
    }
    
    public function setAccessLevel($command, $level = 20)
    {
        $this->access_levels[$command] = $level;
        
        return $this;
    }
    
    public function getAccessLevel($command)
    {
        return @$this->access_levels[$command] ? $this->access_levels[$command] : 20;
    }
}