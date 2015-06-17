<?php

namespace Armagetron\LadderLog;

use Armagetron\Exception\PermissionDeniedException;
use Armagetron\GameObject\Player;

abstract class CustomCommand
{
    /**
     * Returns an array of command names and the access level.
     * If no access level is defined for a command, it will not be executed
     *
     * For instance:
     *
     * // the user needs access_level 15 to issue custom_command
     * array('custom_command' => 15)
     *
     * @return array
     */
    abstract protected function getAccessLevels();

    final public function handleCommand(Player $player, $command_name, $args)
    {
        $access_levels = $this->getAccessLevels();

        if( isset($access_levels[$command_name]) && method_exists($this, $command_name) )
        {
            try
            {
                $this->checkAccessLevel($player, $command_name, $access_levels[$command_name]);
                $this->$command_name($player, $args);
            }
            catch( PermissionDeniedException $pe )
            {
                $player->message($pe->getMessage());
            }
            catch( \Exception $e )
            {
                $player->message(sprintf("An unknown error occurred while issuing command %s", $command_name));
            }
        }
        else
        {
            $player->message(sprintf("Unknown command: %s", $command_name));
        }
    }

    protected function checkAccessLevel(Player $player, $command_name, $access_level)
    {
        if( $access_level < $player->access_level )
        {
            throw new PermissionDeniedException(sprintf("Your access level is too low to issue command %s", $command_name));
        }
    }
}