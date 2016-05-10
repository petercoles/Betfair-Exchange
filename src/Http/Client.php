<?php

namespace PeterColes\Betfair\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PeterColes\Betfair\Api\Auth;
use PeterColes\Betfair\Api\BettingTypes\MarketFilter;

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
     * @param array $headers
     * @return Client
     */
    public function addHeaders(array $headers)
    {
        $this->options[ 'headers' ] = array_merge($this->options[ 'headers' ], $headers);
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
     * Setter for request filter(s).
     *
     * @param  MarketFilter $filter
     * @return Client
     */
    public function setFilter(MarketFilter $filter) {
        $this->options[ 'json' ][ 'filter' ] = $filter;
        return $this;
    }

    /**
     * Setter for request locale.
     * It's optional, so we only pass a value if there is one
     *
     * @param string|null $locale
     * @return Client
     */
    public function setLocale($locale) {
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
