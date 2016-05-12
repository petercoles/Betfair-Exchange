<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Http\Client as HttpClient;

abstract class BaseApi
{
    protected $httpClient;

    protected $method;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    public function execute($params)
    {
        $this->method = array_shift($params);

        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(static::ENDPOINT.$this->method.'/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setParams($this->prepare($params))
            ->send();
    }

    protected function prepare($params)
    {
        return !empty($params) ? $params[ 0 ] : null;
    }
}
