<?php namespace Armagetron;

class GameObject
{
    private static $objects;

    public function __construct($props = array())
    {
        foreach($props as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

    public static function add($id, $values = array())
    {
        $object = new static($values);
        $object->id = $id;
        
        static::$objects[$id] = $object;

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
        $object = @static::$objects[$id];

        if( ! $object )
        {
            if( Config::get('debug') )
            {
                Command::comment('Tried to get non existant '.get_called_class().' with ID: '.$id);
            }
            return static::add($id);
        }

        return $object;
    }

    public static function getAll()
    {
        return static::$objects;
    }

    public static function remove($id)
    {
        unset(static::$objects[$id]);
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
