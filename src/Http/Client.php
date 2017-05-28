<?php

namespace PeterColes\Betfair\Http;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use PeterColes\Betfair\Api\Auth;
use GuzzleHttp\Exception\ClientException;

class Client
{
    protected $guzzleClient;

    protected $method = 'get';

    protected $uri = '';

    protected $options = [ ];

    /**
     * instantiate Guzzle client (unless one is injected).
     *
     * @param GuzzleClient $httpClient
     */
    public function __construct($httpClient = null)
    {
        $this->guzzleClient = $httpClient ?: new GuzzleClient;
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
        $this->options[ 'headers' ] += $header;
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
     * Setter for params.
     *
     * @param array $params
     * @return Client
     */
    public function setParams($params)
    {
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->options[ 'json' ][ $key ] = $value;
            }
        }
        return $this;
    }

    /**
     * Dispatch the request and provide hooks for error handling for the response.
     *
     * @return mixed
     */
    public function send()
    {
        try {
            $response = $this->guzzleClient->request($this->method, $this->uri, $this->options);
        } catch (ClientException $e) {
            $this->handleApiException($e->getResponse()->getBody()->getContents());
        }

        return $this->getBody($response);
    }

    /**
     * Get http response body, cast to json and decode.
     *
     * @param ResponseInterface $response
     * @return array
     */
    protected function getBody(ResponseInterface $response)
    {
        return json_decode((string) $response->getBody());
    }

    /**
     * Stub for API exception handling.
     *
     * @param String $exception
     */
    protected function handleApiException($exception) {
        throw new Exception('API request failure. API Exception Message: '.$exception);
    }
}
