<?php

namespace PeterColes\Betfair;

class Betfair
{
    public static function __callStatic($method, $params)
    {
        $class = 'PeterColes\\Betfair\\Api\\'.ucfirst($method);

        if ($method == 'init') {
            return new $class($params[ 0 ], $params[ 1 ], $params[ 2 ]);
        }

        if ($method == 'account') {
            return call_user_func([new $class, 'execute'], $params);
        }

        if (class_exists($class)) {
            return new $class;
        }
    }
}
