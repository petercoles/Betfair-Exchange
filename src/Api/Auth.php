<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Http\Client as HttpClient;

class Auth
{
    const ENDPOINT = 'https://identitysso.betfair.com/api/';

    protected $httpClient;

    public static $appKey;

    public static $sessionToken;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    public function init($appKey, $username, $password)
    {
        self::$appKey = $appKey;
        self::$sessionToken = $this->login($appKey, $username, $password);
    }

    public function login($appKey, $username, $password)
    {
        $result = $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'login/')
            ->authHeaders([ 'X-Application' => $appKey ])
            ->setFormData([ 'username' => $username, 'password' => $password ])
            ->send();

        return $result->token;
    }

    public function keepAlive()
    {
        $this->httpClient
            ->setEndPoint(self::ENDPOINT.'keepAlive/')
            ->authHeaders()
            ->send();
    }

    public function logout()
    {
        $this->httpClient
            ->setEndPoint(self::ENDPOINT.'logout/')
            ->authHeaders()
            ->send();
    }
}
