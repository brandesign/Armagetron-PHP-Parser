<?php

namespace Armagetron;

class Toolkit
{
    static public function underscoreToCamelCase($string)
    {
        return preg_replace_callback('/_([a-z])/', function($matches) {
            return strtoupper($matches[1]);
        }, $string);
    }

    static public function camelCaseToUnderscore($string)
    {
        return preg_replace_callback('/([A-Z])/', function($matches) {
            return '_' . strtolower($matches[1]);
        }, $string);
    }
}