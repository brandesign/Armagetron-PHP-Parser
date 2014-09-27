<?php

namespace Armagetron\LadderLog;

use Armagetron\Event\EventCollection;
use Armagetron\Event\EventFactory;
use Armagetron\GameObject\GameObjectCollection;
use Armagetron\Parser\ParserInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Stream\Stream;

class LadderLog
{
    protected $stream;
    protected $event_collection = null;
    protected $listeners = array();
    protected $game_objects;

    protected $encoding = null;
    protected $game_time = 0;

    static protected $instance = null;

    protected function __construct()
    {
        $this->loop             = Factory::create();
        $this->stream           = new Stream(fopen('php://stdin', 'r'), $this->loop);
        $this->game_objects     = new GameObjectCollection();

        $this->stream->on('data', array($this, 'handleData'));
    }

    /**
     * @return LadderLog
     */
    static public function getInstance()
    {
        if( is_null(self::$instance) )
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function setEventCollection(EventCollection $collection)
    {
        $this->event_collection = $collection;
    }

    /**
     * @return EventCollection
     */
    public function getEventCollection()
    {
        if( is_null($this->event_collection) )
        {
            $this->event_collection = new EventCollection();
        }

        return $this->event_collection;
    }

    public function getLoop()
    {
        return $this->loop;
    }

    /**
     * @return GameObjectCollection
     */
    public function getGameObjects()
    {
        return $this->getGameObjects();
    }

    public function handleData($data)
    {
        $lines = explode("\n", $data);

        if( empty($lines) )
        {
            if( $data )
            {
                $this->handleLine($data);
            }
        }
        else
        {
            foreach($lines as $line)
            {
                $this->handleLine($line);
            }
        }
    }

    public function handleLine($line)
    {
        $line = trim($line);

        if( ! $line )
        {
            return;
        }

        $line   = new Line($line);
        $event  = EventFactory::createEvent($line, $this->event_collection, $this->game_objects);
        $name   = strtolower($line->getName());

        if( isset($this->listeners[$name]) )
        {
            foreach( $this->listeners[$name] as $handler )
            {
                // calls ParserInterface::$handler($event)
                call_user_func_array($handler, array($event));
            }
        }
    }

    public function register($trigger, ParserInterface $parser, $method_name)
    {
        if( ! isset($this->listeners[$trigger]) )
        {
            $this->listeners[$trigger] = array();
        }

        $this->listeners[$trigger][] = array($parser, $method_name);
    }

    /**
     * @param int $game_time
     */
    public function setGameTime( $game_time )
    {
        $this->game_time = $game_time;
    }

    /**
     * @param string $encoding
     */
    public function setEncoding( $encoding )
    {
        $this->encoding = $encoding;
    }

    /**
     * @return string|null
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @return int
     */
    public function getGameTime()
    {
        return $this->game_time;
    }
}