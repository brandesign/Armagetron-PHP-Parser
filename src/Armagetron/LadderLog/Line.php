<?php

namespace Armagetron\LadderLog;

class Line
{
    protected $name;
    protected $arguments = array();

    public function __construct($line)
    {
        $parts = explode(' ', trim($line), 2);
        $this->name = $parts[0];

        if( isset($parts[1]) )
        {
            $this->arguments = explode(' ', $parts[1]);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}