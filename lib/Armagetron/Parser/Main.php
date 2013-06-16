<?php namespace Armagetron\Parser;

use Armagetron\Config;
use Armagetron\Command;
use Armagetron\Player;
use Armagetron\Team;
use Armagetron\Attribute;

class Main
{
    public static function run()
    {
        static::init();
        
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
            $event = strtolower($args[0]);
            $args = @$args[1];

            if( Config::get('extraParser') )
            {
                static::callParser(Config::get('extraParser'), $event, $args);
            }
            else
            {
                static::callParser('Common', $event, $args);
            }

            static::callParser('static', $event, $args);

            static::mainLoop();
        }

        fclose($stdin);
    }

    private static function callParser($parser, $event, $args)
    {
        if( $parser == 'static' )
        {
            $call = get_called_class().'::'.$event;
        }
        else
        {
            $parser = 'Armagetron\\Parser\\'.$parser;
            $call = $parser.'::'.$event;
        }

        try
        {
            $method = new \ReflectionMethod($call);
        }
        catch( \ReflectionException $e )
        {
            //Command::comment($e->getMessage());
            return;
        }

        $numArgs = $method->getNumberOfParameters();
        $args = explode(' ', $args, $numArgs);

        call_user_func_array(array($parser, $event), $args);
    }

    public function __call($function, $args)
    {
        return;
    }

    public static function __callStatic($function, $args)
    {
        return;
    }
}
