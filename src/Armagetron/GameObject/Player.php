<?php

namespace Armagetron\GameObject;

use Armagetron\Server\Command;

class Player extends GameObject implements GameObjectInterface
{
    public $id;
    public $name            = null;
    public $screen_name     = null;
    public $ip              = null;
    public $joined          = 0;
    public $access_level    = 20;
    public $is_human        = false;
    public $team            = null;
    public $x               = null;
    public $y               = null;
    public $x_dir           = null;
    public $y_dir           = null;

    public $kills           = 0;
    public $team_kills      = 0;
    public $deaths          = 0;
    public $suicides        = 0;

    public function __construct($id)
    {
        $this->id       = $id;
        $this->joined   = time();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->getId();
    }

    public function getScreenName()
    {
        if( ! $this->screen_name )
        {
            $this->screen_name = $this->getId();
        }

        return $this->screen_name;
    }

    public function setTeam(Team $team)
    {
        $this->team = $team;
    }

    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Returns the online time in seconds
     *
     * @return int
     */
    public function onlineTime()
    {
        return time() - $this->joined;
    }

    public function message($message)
    {
        if( $this->is_human )
        {
            Command::playerMessage($this, $message);
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

    public function kickTo($host, $port)
    {
        if( $this->is_human )
        {
            Command::kickTo($this, $host, $port);
        }
    }

    public function moveTo($host, $port)
    {
        if( $this->is_human )
        {
            Command::moveTo($this, $host, $port);
        }
    }

    public function ban($minutes = 5)
    {
        if( $this->is_human )
        {
            Command::banPlayer($this, $minutes);
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

    public function respawn($x, $y, $dir_x, $dir_y, $show_message = true)
    {
        Command::respawnPlayer($this, $x, $y, $dir_x, $dir_y, $show_message);
    }
}