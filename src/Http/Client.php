<?php

namespace PeterColes\Betfair\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PeterColes\Betfair\Api\Auth;
use stdClass;

class Client
{
    protected $guzzleClient;

    protected $method = 'get';

    protected $uri = '';

    protected $options = [ ];

    /**
     * instantiate Guzzle client (unless one is injected).
     *
     * @param GuzzleClient $client
     * @return void
     */
    public function __construct($client = null)
    {
        $this->guzzleClient = $client ?: new Client;
        $this->options[ 'headers' ] = [ 'Accept' => 'application/json' ];
    }

    /**
     * Setter for request method.
     *
     * @param string $method
     * @return Client
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Setter for request end point URI.
     *
     * @param string $endPoint
     * @return Client
     */
    public function setEndPoint(string $endPoint)
    {
        $this->uri = $endPoint;
        return $this;
    }

    /**
     * Setter for request headers.
     *
     * @param array $header
     * @return Client
     */
    public function addHeader(array $header)
    {
        $this->options[ 'headers' ][ ] = $header;
        return $this;
    }

    /**
     * Setter for authentication headers.
     *
     * @param array $headers
     * @return Client
     */
    public function authHeaders(array $headers = [ ])
    {
        if (count($headers) == 0) {
            $headers = [ 'X-Application' => Auth::$appKey, 'X-Authentication' => Auth::$sessionToken ];
        }
        $this->options[ 'headers' ] = array_merge($this->options[ 'headers' ], $headers);
        return $this;
    }

    /**
     * Setter for request form data.
     *
     * @param array $formData
     * @return Client
     */
    public function setFormData(array $formData)
    {
        $this->options[ 'form_params' ] = $formData;
        return $this;
    }

    /**
     * Setter for request filter(s). mandatory, but can be empty
     *
     * @param  array $filter
     * @return Client
     */
    public function setFilter($filter = null)
    {
        $this->options[ 'json' ][ 'filter' ] = $filter ?: new stdClass;
        return $this;
    }

    /**
     * Setter for optional market projection, i.e. what market-related data should be returned.
     *
     * @param  array $marketProjection
     * @return Client
     */
    public function setMarketProjection($marketProjection = null)
    {
        if ($marketProjection) {
            $this->options[ 'json' ][ 'marketProjection' ] = $marketProjection;
        }
        return $this;
    }

    /**
     * Setter for optional request sort.
     *
     * @param string $sort
     * @return Client
     */
    public function setSort($sort)
    {
        if ($sort) {
            $this->options[ 'json' ][ 'sort' ] = $sort;
        }
        return $this;
    }

    /**
     * Setter for mandatory request max results limiter.
     *
     * @param integer|null $maxResults
     * @return Client
     */
    public function setMaxresults($maxResults = 100)
    {
        if ($maxResults) {
            $this->options[ 'json' ][ 'maxResults' ] = $maxResults;
        }
        return $this;
    }

    /**
     * Setter for optional request locale.
     *
     * @param string|null $locale
     * @return Client
     */
    public function setLocale($locale)
    {
        if ($locale) {
            $this->options[ 'json' ][ 'locale' ] = $locale;
        }
        return $this;
    }

    /**
     * Dispatch the request and provide hooks for error handling for the response.
     *
     * @return object stdClass
     */
    public function send()
    {
        $response = $this->guzzleClient->request($this->method, $this->uri, $this->options);

        $status = $this->getStatus($response);

        if ($status != 200) {
            $this->handleHttpException($status);
        }

        $body = $this->getBody($response);

        if (is_object($body) && $body->status != 'SUCCESS') {
            $this->handleApiException($body->error);
        }

        return $body;
    }

    /**
     * Get status code from http response.
     *
     * @param Response $response
     * @return integer
     */
    protected function getStatus(Response $response)
    {
        return (int) $response->getStatusCode();
    }

    /**
     * Get http response body, cast to json and decode.
     *
     * @param Response object $response
     * @return array
     */
    protected function getBody(Response $response)
    {
        return json_decode((string) $response->getBody());
    }

    /**
     * Stub for http exception handling.
     *
     * @param Integer $status
     * @return void
     */
    protected function handleHttpException($status) {
        throw new Exception('Http request failure. Http Exception Code: '.$status);
    }

    /**
     * Stub for API exception handling.
     *
     * @param String $exception
     * @return void
     */
    protected function handleApiException($exception) {
        throw new Exception('API request failure. API Exception Message: '.$exception);
    }
}
