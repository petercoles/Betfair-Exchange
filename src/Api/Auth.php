<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Api\BaseApi;

class Auth extends BaseApi
{
    const ENDPOINT = 'https://identitysso.betfair.com/api/';

    const SESSION_LENGTH = 4 * 60 * 60; // 4 hours

    protected $httpClient;

    public static $appKey = null;

    public static $sessionToken = null;

    public static $lastLogin = null;

    public function init($appKey, $username, $password)
    {
        if ($appKey == self::$appKey && $this->sessionRemaining() > 5) {
            $this->keepAlive();
        } else {
            self::$appKey = $appKey;
            self::$sessionToken = $this->login($appKey, $username, $password);
        }
    }

    public function login($appKey, $username, $password)
    {
        $result = $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'login/')
            ->authHeaders([ 'X-Application' => $appKey ])
            ->setFormData([ 'username' => $username, 'password' => $password ])
            ->send();

        self::$lastLogin = time();

        return $result->token;
    }

    public function keepAlive()
    {
        $this->httpClient
            ->setEndPoint(self::ENDPOINT.'keepAlive/')
            ->authHeaders()
            ->send();

        self::$lastLogin = time();
    }

    public function logout()
    {
        $this->httpClient
            ->setEndPoint(self::ENDPOINT.'logout/')
            ->authHeaders()
            ->send();

        self::$appKey = null;
        self::$sessionToken = null;
        self::$lastLogin = null;
    }

    public function sessionRemaining()
    {
        return self::$lastLogin + self::SESSION_LENGTH - time();
    }
}
