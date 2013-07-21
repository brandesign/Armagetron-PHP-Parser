<?php namespace Armagetron;

class ServerList
{
	private $cache = null;
	private static $instance = null;

	public $list_urls = array();

	const CACHE_TIME = 90; // Seconds

	private function __construct()
	{
		if( is_array(Config::get('server_list_urls')) )
		{
			$this->list_urls = Config::get('server_list_urls');
		}
	}

	public static function getInstance()
    {
        if( ! self::$instance )
        {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function addUrl($url)
    {
    	if( ! in_array($url, $this->list_urls) )
    	{
    		$this->list_urls[] = $url;
    	}

    	return $this;
    }

    public static function getList(array $exclude = array())
    {
    	$instance = self::getInstance();

    	$age = null;

    	if( $instance->cache )
    	{
    		if( $instance->cache->time > time() - self::CACHE_TIME )
    		{
    			return $instance->cache;
    		}
    		else
    		{
    			$age = time() - $instance->cache->time;
    		}
    	}

    	shuffle($instance->list_urls);

    	foreach($instance->list_urls as $url)
    	{
    		if(in_array($url, $exclude))
    		{
    			continue;
    		}

	        $xml = @simplexml_load_file($url);

	        if( ! $xml OR ( $age && (int)$xml['age'] > $age ) )
	        {
	        	$exclude[] = $url;
	            return self::getList($exclude);
	        }
	        else
	        {
	        	$instance->cache = static::formatList($xml);
	        }
    	}

    	if( $instance->cache )
    	{
    		return $instance->cache;
    	}
    	else
    	{
    		throw new \Exception('Unable to get server list.');
    	}
    }

    public static function formatList(\SimpleXMLElement $xml)
    {
    	$ret = new \stdClass;
    	$ret->time = time() - (int)$xml['age'];
    	$ret->servers = array();
    	
    	foreach($xml->Server as $server)
    	{
    		$new_server = new \stdClass;

    		$new_server->name 				= (string)$server['name'];
    		$new_server->name_striped 		= static::stripColors($new_server->name);
    		$new_server->ip 				= (string)$server['ip'];
    		$new_server->port 				= (string)$server['port'];
    		$new_server->version 			= (string)$server['version'];
    		$new_server->version_min 		= (int)$server['version_min'];
    		$new_server->version_max 		= (int)$server['version_max'];
    		$new_server->description 		= (string)$server['description'];
    		$new_server->description_striped = static::stripColors($new_server->description);
    		$new_server->num_players 		= (int)$server['num_players'];
    		$new_server->max_players 		= (int)$server['max_players'];
    		$new_server->players 			= array();
    		
    		foreach($server->Player as $player)
    		{
    			$new_player = new \stdClass;

    			$new_player->name = (string)$player['name'];
    			$new_player->gid = @$player['global_id'] ? $player['global_id'] : null;

    			$new_server->players[] = $new_player;
    		}

    		$ret->servers[] = $new_server;
    	}

    	return $ret;
    }

    public static function getServersByName($name)
    {
    	$list = self::getList();
    	$matches = array();

    	foreach($list->servers as $server)
    	{
    		if( preg_match('@'.preg_quote($name).'@i', $server->name_striped) )
    		{
    			$matches[] = $server;
    		}
    	}

    	return $matches;
    }

    public static function getServersByIp($ip)
    {
    	$list = self::getList();
    	$matches = array();

    	foreach($list->servers as $server)
    	{
    		if( preg_match('@'.preg_quote($ip).'@i', $server->ip) )
    		{
    			$matches[] = $server;
    		}
    	}

    	return $matches;
    }

    public static function stripColors($string)
    {
    	return preg_replace('@0x[\w\d]{6}@', '', $string);
    }
}
