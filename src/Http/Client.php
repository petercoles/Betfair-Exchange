<?php

namespace PeterColes\Betfair\Http;

use GuzzleHttp\Client;
use Exception;

class Client
{
    protected $guzzleClient;

    protected $method = 'get';

    protected $uri = '';

    protected $options = [];

    /**
     * instantiate Guzzle client (unless one is injected).
     *
     * @param GuzzleClient $client
     * @return void
     */
    public function __construct($client = null)
    {
        $this->guzzleClient = $client ?: new Client;
    }

    /**
     * Setter for request method.
     *
     * @param string $method
     * @return PeterColes\Betfair\Http\Client
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
     * @return PeterColes\Betfair\Http\Client
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
     * @return PeterColes\Betfair\Http\Client
     */
    public function setHeaders(array $headers)
    {
        $this->options['headers'] = $headers;
        return $this;
    }

    /**
     * Setter for request form data.
     *
     * @param arrat $formData
     * @return PeterColes\Betfair\Http\Client
     */
    public function setFormData(array $formData)
    {
        $this->options['form_params'] = $formData;
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
        if ($body->status != 'SUCCESS') {
            $this->handleApiException($body->error);
        } 

        return $body;
    }

    /**
     * Get status code from http response.
     *
     * @param GuzzleResponse $response
     * @return integer
     */
    protected function getStatus($response)
    {
        return (int) $response->getStatusCode();
    }

    /**
     * Get http response body, cast to json and decode.
     *
     * @param GuzzleHttp\Response object $response
     * @return array
     */
    protected function getBody($response)
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
