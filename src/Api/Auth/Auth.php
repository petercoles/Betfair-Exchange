<?php

namespace PeterColes\Betfair\Api\Auth;

use PeterColes\Betfair\Http\Client as HttpClient;

class Auth
{
    protected $httpClient;

    protected $endPoint = 'https://identitysso.betfair.com/api/';

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }
}
