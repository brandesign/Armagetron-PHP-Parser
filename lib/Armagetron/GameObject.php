<?php namespace Armagetron;

class GameObject
{
    protected static $objects = array();
    public $name;

    public function __construct($props = array())
    {
        foreach($props as $key => $value)
        {
            $this->{$key} = $value;
        }
        
        if( ! $this->name )
        {
            $this->name = $this->id;
        }
    }

    public static function add($id, $values = array())
    {
        $object = new static($values);
        $object->id = $id;
        
        static::$objects[] = $object;

        return $object;
    }

    public static function update($oldId, $newId, $values = array())
    {
        $old = static::get($oldId);

        if( ! $old )
        {
            return null;
        }

        foreach( $old as $k => $v )
        {
            if( ! array_key_exists($k, $values) )
            {
                $values[$k] = $v;
            }
        }

        static::remove($oldId);
        static::add($newId, $values);
    }

    public static function get($id)
    {
        foreach( static::getAll() as $object )
        {
            if( $object->id == $id )
            {
                return $object;
            }
        }
        
        return static::add($id);
    }

    public static function getAll()
    {
        return static::$objects;
    }

    public static function remove($id)
    {
        foreach(static::getAll() as $key => $object)
        {
            if($object->id == $id)
            {
                unset(static::$objects[$key]);
            }
        }
    }

    public static function count()
    {
        return count(static::$objects);
    }

    public static function clean()
    {
        static::$objects = array();
    }

    public function __get($name)
    {
        return @$this->{$name} ? $this->{$name} : null;
    }

    public function __set($key, $value)
    {
        $this->{$key} = $value;
    }
}
