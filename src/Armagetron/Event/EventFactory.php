<?php

namespace Armagetron\Event;

use Armagetron\Exception\EventDefinitionNotFoundException;
use Armagetron\GameObject\GameObjectCollection;
use Armagetron\LadderLog\Line;

class EventFactory
{
    static public function createEvent(Line $line, EventCollection $collection, GameObjectCollection $game_objects)
    {
        try
        {
            $definitions = $collection->getByName($line->getName());
        }
        catch( EventDefinitionNotFoundException $e )
        {
            // @TODO: Log errors
            $definitions = array();
        }

        return new Event($definitions, $game_objects, $line->getArguments());
    }
}