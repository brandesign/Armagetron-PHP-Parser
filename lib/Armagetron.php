<?php namespace Armagetron;

class Armagetron
{
    protected $config;

    public function __construct()
    {
        $this->config = Config::getInstance();
    }

    public function run()
    {
        while( FALSE !== ($line = fgets(STDIN)) )
        {
            $parts = explode(' ', trim($line));
            $event = strtolower($parts[0]);
            unset($parts[0]);

            var_dump($line);

            $this->extraParser($event, $parts);
            call_user_func_array(array($this, $event), $parts);

            echo 'Players: ';
            var_dump(Player::getAll());
            echo 'Teams: ';
            var_dump(Team::getAll());
        }
    }

    private function extraParser($function, $args)
    {
        if( $this->config->extraParser )
        {
            try
            {
                $parser = 'Armagetron\\'.$this->config->extraParser.'Parser';

                $extraParser = $parser::getInstance();
                call_user_func_array(array($extraParser, $function), $args);
            }
            catch( \Exception $e )
            {
                if( $this->config->debug )
                {
                    Command::comment($e->getMessage());
                }
            }
        }
    }

    public function __call($function, $args)
    {
        return;
    }
}
