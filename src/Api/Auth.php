<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Api\BaseApi;

class Auth extends BaseApi
{
    /**
     * Betfair API endpoint for authentication requests
     */
    const ENDPOINT = 'https://identitysso.betfair.com/api/';

    /**
     * 4 hours, expressed in seconds
     */
    const SESSION_LENGTH = 4 * 60 * 60;

    /**
     * Application key, provided by Betfair on registration
     */
    public static $appKey = null;

    /**
     * Session token, provided by Betfair at login
     */
    public static $sessionToken = null;

    /**
     * Time of last login, expressed in seconds since the Unix Epoch
     */
    public static $lastLogin = null;

    /**
     * Wrapper method for other methods to initiate and manage a Betfair session
     *
     * @param  string $appKey
     * @param  string $username
     * @param  string $password
     */
    public function init($appKey, $username, $password)
    {
        if ($appKey == self::$appKey && $this->sessionRemaining() > 5) {
            $this->keepAlive();
        } else {
            self::$appKey = $appKey;
            self::$sessionToken = $this->login($appKey, $username, $password);
        }
    }

    /**
     * Method to directly execute Betfair login request.
     * For use only when the init() method isn't appropriate
     *
     * @param  string $appKey
     * @param  string $username
     * @param  string $password
     */
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

    /**
     * Execute Betfair API call to extend the current session
     */
    public function keepAlive()
    {
        $this->httpClient
            ->setEndPoint(self::ENDPOINT.'keepAlive/')
            ->authHeaders()
            ->send();

        self::$lastLogin = time();
    }

    /**
     * Execute Betfair API call to logout from their system.
     * Clear all local references to the session.
     */
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

    /**
     * Calculate and provide the time remaining until the current session token expires
     */
    public function sessionRemaining()
    {
        return self::$lastLogin + self::SESSION_LENGTH - time();
    }
}
