<?php namespace Armagetron;

class Config
{
    private $items = array();
    private static $instance = null;

    private function __construct()
    {
        $this->items = $this->parseFile();
    }

    public static function getInstance()
    {
        if( ! self::$instance )
        {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public static function get($key)
    {
        $instance = self::getInstance();

        return $instance->{$key};
    }

    public static function all()
    {
        return self::getInstance()->items;
    }

    public function __get( $key )
    {
        return @$this->items[$key] ? $this->items[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->items[$key] = $value;
    }

    private function parseFile()
    {
        $file = APP_PATH.'/config.json';
        if( ! file_exists($file) )
        {
            throw new \Exception('Configuration file '.$file.' not found.');
        }

        return json_decode(file_get_contents($file), true);
    }
}
