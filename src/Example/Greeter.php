<?php

namespace Example;

use Armagetron\Event\Event;
use Armagetron\GameObject\Player;
use Armagetron\Parser\ParserInterface;
use Armagetron\Server\Command;

class Greeter implements ParserInterface
{
    public function playerEntered(Event $event)
    {
        /* @var Player $player */
        $player = $event->player;

        $player->message("Welcome ".$player->name);
    }
}