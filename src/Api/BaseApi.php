<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Http\Client as HttpClient;

abstract class BaseApi
{
    /**
     * HTTP client
     */
    protected $httpClient;

    /**
     * The API method being invoked. Not to be confused with the HHTP verb,
     * also referred to as "method" by the Guzzle HTTP client/
     */
    protected $method;

    /**
     * The parameters that will be passed to the API method being invoked.
     */
    protected $params;

    /**
     * Ensure that we have an HTTP client with which to work
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    /**
     * Invoke the HTTP client to Execute the API request
     *
     * @param  array $params
     * @return mixed
     */
    public function execute($params)
    {
        $this->method = array_shift($params);
        $this->prepare($params);

        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(static::ENDPOINT.$this->method.'/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setParams($this->params)
            ->send();
    }

    /**
     * Prepare parameters for ingestion by API requests.
     * Minimum activity is to remove a layer of array.
     *
     * @param  array $params
     * @return array|null
     */
    protected function prepare($params)
    {
        $this->params = !empty($params) ? $params[ 0 ] : null;
    }
}
