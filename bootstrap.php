<?php
define('APP_PATH', __DIR__);
define('LIB_PATH', APP_PATH.'/lib');

function autoload($className)
{
    //$className = str_replace('Armagetron\\', '', $className);
    $parts = explode('\\', $className);
    //$file = LIB_PATH . '/' . end($parts) . '.php';
    $file = LIB_PATH . '/' . implode('/', $parts) . '.php';

    if (file_exists($file))
    {
        require_once $file;
    }
    else
    {
        echo 'Try to load '.$className.' from '.$file;
        throw new \Exception('Class '.$file.' not found.');
    }
}

spl_autoload_register('autoload');
