<?php namespace Armagetron;

class ServerList
{
	private $cache = null;
	private static $instance = null;

	private function __construct()
	{

	}

	public static function getInstance()
    {
        if( ! self::$instance )
        {
            self::$instance = new static;
        }

        return self::$instance;
    }
}
