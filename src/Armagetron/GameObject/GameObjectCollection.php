<?php

namespace Armagetron\GameObject;

use Armagetron\Exception\GameObjectNotFoundException;

class GameObjectCollection implements \Countable, \IteratorAggregate
{
    /* @var GameObjectInterface[] $objects */
    protected $objects = array();
    protected $garbage = array();

    public function __construct(array $objects = array())
    {
        $this->objects = $objects;
    }

    public function add(GameObjectInterface $object)
    {
        $this->objects[] = $object;

        return $this;
    }

    /**
     * Adds the id of the object to the garbage collection
     *
     * @param $id
     */
    public function remove($id)
    {
        $this->garbage[] = $id;
    }

    public function collectGarbage()
    {
        $this->delete($this->garbage);
        $this->garbage = array();
    }

    /**
     * @param mixed $ids
     */
    public function delete($ids)
    {
        if( ! is_array($ids) )
        {
            $ids = array($ids);
        }

        $iterator = $this->getIterator();

        while( $iterator->valid() )
        {
            if( in_array($iterator->current()->getId(), $ids) )
            {
                unset($this->objects[$iterator->key()]);
            }

            $iterator->next();
        }
    }

    public function getPlayers()
    {
        return new static(array_filter($this->objects, function (GameObjectInterface $object) {
            return ($object instanceof Player);
        }));
    }

    public function getTeams()
    {
        return new static(array_filter($this->objects, function (GameObjectInterface $object) {
            return ($object instanceof Team);
        }));
    }

    public function getZones()
    {
        return new static(array_filter($this->objects, function (GameObjectInterface $object) {
            return ($object instanceof Zone);
        }));
    }

    /**
     * @param $id
     * @return GameObjectInterface
     * @throws \Armagetron\Exception\GameObjectNotFoundException
     */
    public function getById($id)
    {
        $iterator = $this->getIterator();

        while( $iterator->valid() )
        {
            if( $iterator->current()->getId() == $id )
            {
                return $iterator->current();
            }

            $iterator->next();
        }

        throw new GameObjectNotFoundException(sprintf("Cannot find GameObject with id %s.", $id));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->objects);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->objects);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->objects);
    }
}