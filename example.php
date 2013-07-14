#!/usr/bin/php
<?php
require_once('bootstrap.php');

use Armagetron\Parser;
use Armagetron\Player;
use Armagetron\Team;
use Armagetron\Command;

class CustomCommand extends Parser\CustomCommand
{
    public function __construct()
    {
        $this->setAccessLevel('testing', 19);
    }
    
    public function testing($args, $caller)
    {
        $caller->message('You called custom command testing with args '.$args);
    }
}

class Example extends Parser\Main
{
    public function __construct()
    {
        $this->useParser('StyCt');
        Parser\CustomCommand::registerHandler(new CustomCommand());
    }

    protected function player_entered($event)
    {
        $event->player->message('Welcome '.$event->screen_name.'!');
    }

    protected function new_round($event)
    {
        Command::comment('I am using '.Armagetron\Attribute::memoryUsage().' memory.');
        $this->delayedCommand(5, 'spawnZones', array(5));
    }

    public function spawnZones($num)
    {
        // logic to spawn $num zones
    }
}

Example::run();
