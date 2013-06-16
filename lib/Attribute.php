<?php namespace Armagetron;

class Attribute
{
    private static $items = array();

    public static function set($key, $value)
    {
        static::$items[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return @static::$items[$key] ? static::$items[$key] : $default;
    }

    public static function getAll()
    {
        return (object)static::$items;
    }

    public static function memoryUsage()
    {
        $size = memory_get_usage();
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
        return round($size, 2).$units[$i];
    }
}
