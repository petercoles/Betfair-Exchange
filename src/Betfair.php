<?php

namespace PeterColes\Betfair;

class Betfair
{
    public static function __callStatic($method, $params)
    {
        return new \PeterColes\Betfair\Api\Auth\Auth;
    }
}
