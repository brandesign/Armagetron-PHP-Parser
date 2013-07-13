<?php namespace Armagetron\Parser;

use Armagetron\Config;
use Armagetron\Command;
use Armagetron\Player;
use Armagetron\Team;
use Armagetron\Attribute;
use Armagetron\Event;

class Main
{
    protected $config;
    protected $intern_parser = null;
    private $intern_parser_instance = null;
    private static $instance = null;

    protected function __construct()
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

    public static function run()
    {
        $instance = self::getInstance()->init();

        $stdin = fopen('php://stdin', 'r');

        while( $line = fgets($stdin) )
        {
            if( Attribute::get('encoding') == 'latin1' )
            {
                $line = utf8_encode($line);
            }

            if('' == ($line = trim($line)))
            {
                continue;
            }

            $args = explode(' ', $line, 2);
            $command = strtolower($args[0]);
            $args = @$args[1];
            
            try
            {
                $event = new Event($command, $args, $instance->intern_parser);
            }
            catch( \Exception $e )
            {
                Command::comment($e->getMessage());
                continue;
            }

            $instance->intern_parser_instance->$command( $event );
            $instance->$command( $event );
        }

        fclose($stdin);
    }

    protected function init()
    {
        $this->config = Config::all();

        if( ! $this->intern_parser )
        {
            if(Config::get('intern_parser'))
            {
                $intern_parser = Config::get('intern_parser');
            }
            else
            {
                $intern_parser = 'Common';
            }
            
            $this->useParser($intern_parser);
        }

        return $this;
    }

    public function useParser($parser)
    {
        $class = __NAMESPACE__.'\\'.$parser;
        if( ! class_exists($class) )
        {
            throw new \Exception('Parser '.$class.' does not exist.');
        }

        $this->intern_parser = $class;
        $this->intern_parser_instance = new $class();

        return $this;
    }

    public function registerEvent($command, array $args = array())
    {
        $parser = get_called_class();
        Event::register($parser, $command, $args);

        return $this;
    }

    public function __call($function, $args)
    {
        return;
    }
}
