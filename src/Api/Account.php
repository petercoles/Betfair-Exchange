<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Http\Client as HttpClient;

class Account
{
    const ENDPOINT = 'https://api.betfair.com/exchange/account/rest/v1.0/';

    protected $httpClient;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    public function getAccountDetails()
    {
        return $this->httpClient
            // ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'getAccountDetails/')
            ->authHeaders()
            // ->addHeader([ 'Content-Type' => 'application/json' ])
            ->send();
    }
}
