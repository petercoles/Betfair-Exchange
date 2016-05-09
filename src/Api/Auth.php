<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Http\Client as HttpClient;

class Auth
{
    const ENDPOINT = 'https://identitysso.betfair.com/api/';

    protected $httpClient;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    public function login($appKey, $username, $password)
    {
        $result = $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'login/')
            ->addHeaders(['X-Application' => $appKey])
            ->setFormData(['username' => $username, 'password' => $password])
            ->send();

        return $result->token;
    }

    public function keepAlive($appKey, $sessionToken)
    {
        $this->httpClient
            ->setEndPoint(self::ENDPOINT.'keepAlive/')
            ->addHeaders(['X-Application' => $appKey, 'X-Authentication' => $sessionToken])
            ->send();
    }

    public function logout($appKey, $sessionToken)
    {
        $this->httpClient
            ->setEndPoint(self::ENDPOINT.'logout/')
            ->addHeaders(['X-Application' => $appKey, 'X-Authentication' => $sessionToken])
            ->send();
    }
}
