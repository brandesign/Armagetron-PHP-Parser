<?php

namespace Armagetron\Event;

use Armagetron\Exception\EventDefinitionNotFoundException;

class EventCollection implements \Countable, \IteratorAggregate
{
    /* @var Event[] $events */
    protected $events = array();

    public function __construct(array $events = array())
    {
        foreach( $events as $name => $definitions )
        {
            $this->add($name, $definitions);
        }
    }

    public function add($name, array $definitions = array())
    {
        $this->events[strtolower($name)] = $definitions;

        return $this;
    }

    /**
     * @param $name
     * @return array
     * @throws \Armagetron\Exception\EventDefinitionNotFoundException
     */
    public function getByName($name)
    {
        $name       = strtolower($name);
        $iterator   = $this->getIterator();

        while( $iterator->valid() )
        {
            if( $iterator->key() == $name )
            {
                return $iterator->current();
            }

            $iterator->next();
        }

        throw new EventDefinitionNotFoundException(sprintf("Cannot find event definition with name %s.", $name));
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasEvent($name)
    {
        $iterator = $this->getIterator();

        while( $iterator->valid() )
        {
            if( $iterator->key() == $name )
            {
                return true;
            }

            $iterator->next();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->events);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->events);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->events);
    }
}