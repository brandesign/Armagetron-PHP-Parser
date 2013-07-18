<?php namespace Armagetron;

class Player extends GameObject
{
    public $joined = 0;
    public $access_level = 20;

    public $kills = 0;
    public $team_kills = 0;
    public $deaths = 0;
    public $suicides = 0;

    public function __construct($props = array())
    {
        $this->joined = time();

        parent::__construct($props);
    }

    public function message($message)
    {
        if( $this->is_human )
        {
            Command::player_message($this, $message);
        }

        return $this;
    }
    
    public function kill()
    {
        Command::kill($this);

        return $this;
    }

    public function kick()
    {
        if( $this->is_human )
        {
            Command::kick($this);
        }

        return $this;
    }

    public function ban()
    {
        if( $this->is_human )
        {
            Command::ban($this);
        }

        return $this;
    }

    public function suspend($rounds = null)
    {
        if( $this->is_human )
        {
            Command::suspend($this, $rounds);
        }

        return $this;
    }

    public function unsuspend()
    {
        if( $this->is_human )
        {
            Command::unsuspend($this);
        }

        return $this;
    }

    public function online_time()
    {
        return time() - $this->joined;
    }
}
