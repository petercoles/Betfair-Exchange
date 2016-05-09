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

    public function login($appKey, $username, $password)
    {
        $result = $this->httpClient
            ->setMethod('post')
            ->setEndPoint($this->endPoint.'login/')
            ->setHeaders(['Accept' => 'application/json', 'X-Application' => $appKey])
            ->setFormData(['username' => $username, 'password' => $password])
            ->send();

        return $result->token;
    }

    public function logout($appKey, $sessionToken)
    {
        $this->httpClient
            ->setEndPoint($this->endPoint.'logout/')
            ->setHeaders(['Accept' => 'application/json', 'X-Application' => $appKey, 'X-Authentication' => $sessionToken])
            ->send();
    }
}
