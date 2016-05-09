<?php

namespace PeterColes\Betfair;

class Betfair
{
    public static function __callStatic($method, $params)
    {
        $class = 'PeterColes\\Betfair\\Api\\'.ucfirst($method);

        if (class_exists($class)) {
            return new $class;
        }
    }
}
