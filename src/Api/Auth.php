<?php

namespace PeterColes\Betfair\Api;

use Exception;

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
     * API fail status
     */
    const API_STATUS_FAIL = 'FAIL';

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
     * Wrapper method for other methods to initiate and manage a Betfair session.
     * This method can be called safely multiple times in a script, or in a loop
     * for a long running process and will only trigger the authentication overhead
     * when really needed.
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
            $this->login($appKey, $username, $password);
        }
    }

    /**
     * Accept app key and session token and extend session.
     *
     * @param  string $appKey
     * @param  string $sessionToken
     * @return string
     */
    public function persist($appKey, $sessionToken)
    {
        if ($sessionToken === null) {
            throw new Exception('Invalid session token');
        }

        self::$appKey = $appKey;
        self::$sessionToken = $sessionToken;

        return $this->keepAlive();
    }

    /**
     * Method to directly execute Betfair login request.
     * For use only when the init() method isn't appropriate.
     *
     * @param  string $appKey
     * @param  string $username
     * @param  string $password
     * @return string
     * @throws Exception
     */
    public function login($appKey, $username, $password)
    {
        self::$appKey = $appKey;

        $request = $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'login/')
            ->setFormData([ 'username' => $username, 'password' => $password ]);

        $result = $this->execute($request);

        self::$sessionToken = $result->token;
        self::$lastLogin = time();

        return $result->token;
    }

    /**
     * Execute Betfair API call to extend the current session.
     * Implicitly uses the already set app key and session token.
     *
     * @return string
     * @throws Exception
     */
    public function keepAlive()
    {
        $result = $this->execute($this->httpClient->setEndPoint(self::ENDPOINT.'keepAlive/'));

        self::$lastLogin = time();

        return $result->token;
    }

    /**
     * Execute Betfair API call to logout from their system.
     * Clear all local references to the session.
     *
     * @throws Exception
     */
    public function logout()
    {
        $this->execute($this->httpClient->setEndPoint(self::ENDPOINT.'logout/'));

        self::$appKey = null;
        self::$sessionToken = null;
        self::$lastLogin = null;
    }

    /**
     * Calculate and provide the time remaining until the current session token expires.
     *
     * @return integer
     */
    public function sessionRemaining()
    {
        if (self::$sessionToken === null) {
            return 0;
        }

        return self::$lastLogin + self::SESSION_LENGTH - time();
    }

    /**
     * Accept request, add auth headers and dispatch, then respond to any errors.
     *
     * @param  \PeterColes\Betfair\Http\Client $request
     * @return Mixed
     * @throws Exception
     */
    public function execute($request)
    {
        $result = $request->authHeaders()->send();

        if ($result->status === self::API_STATUS_FAIL) {
            throw new Exception('Error: '.$result->error);
        }

        return $result;
    }
}
