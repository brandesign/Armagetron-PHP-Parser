<?php

require 'vendor/autoload.php';

$parser = new \Armagetron\Parser\StyCt(new Example\Greeter());

$parser->run();
