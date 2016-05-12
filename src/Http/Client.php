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
     * Setter for marketId(s).
     * This param is sometime mandatory, but othertimes breaks the API if an empty value is provided
     *
     * @param  array $marketIds
     * @param  boolean $mandatory
     * @return Client
     */
    public function setMarketIds($marketIds = null, $mandatory = false)
    {
        if ($marketIds || $mandatory) {
            $this->options[ 'json' ][ 'marketIds' ] = $marketIds ?: new stdClass;
        }
        return $this;
    }

    /**
     * Setter for optional betId(s).
     *
     * @param  array $betIds
     * @return Client
     */
    public function setBetIds($betIds = null)
    {
        if ($betIds) {
            $this->options[ 'json' ][ 'betIds' ] = $betIds ?: new stdClass;
        }
        return $this;
    }

    /**
     * Setter for optional market projection, i.e. what market-related data should be returned.
     *
     * @param  string $name
     * @param  array $marketProjection
     * @return Client
     */
    public function setProjection($name, $projection = null)
    {
        if ($projection) {
            $this->options[ 'json' ][ $name ] = $projection;
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
     * @param integer $maxResults
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
     * @param string $locale
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
     * Setter for mandatory time granularity.
     *
     * @param string $granularity
     * @return Client
     */
    public function setTimeGranularity($granularity)
    {
        $this->options[ 'json' ][ 'granularity' ] = $granularity;
        return $this;
    }

    /**
     * Setter for optional currency code.
     *
     * @param string $currencyCode
     * @return Client
     */
    public function setCurrencyCode($currencyCode)
    {
        if ($currencyCode) {
            $this->options[ 'json' ][ 'currencyCode' ] = $currencyCode;
        }
        return $this;
    }

    /**
     * Setter for optional flag.
     *
     * @param string $name
     * @param boolean $flag
     * @return Client
     */
    public function setFlag($name, $flag)
    {
        if ($flag) {
            $this->options[ 'json' ][ $name ] = true;
        }
        return $this;
    }

    /**
     * Setter for date range.
     *
     * @param string $name
     * @param array $range
     * @return Client
     */
    public function setDateRange($name, $range)
    {
        if (!empty($range)) {
            $this->options[ 'json' ][ $name ] = $range;
        }
        return $this;
    }

    /**
     * Setter for sort order.
     *
     * @param string $by
     * @param string $direction
     * @return Client
     */
    public function setOrder($by, $direction = null)
    {
        if ($by) {
            $this->options[ 'json' ][ 'orderBy' ] = $by;

            if (!empty($direction)) {
                $this->options[ 'json' ][ 'sortDir' ] = $direction;
            }
        }
        return $this;
    }

    /**
     * Setter for record range.
     *
     * @param int $from
     * @param int $count
     * @return Client
     */
    public function setRecordRange($from = 0, $count = 1000)
    {
        $this->options[ 'json' ][ 'fromRecord' ] = $from;
        $this->options[ 'json' ][ 'recordCount' ] = $count;
        return $this;
    }

    /**
     * Setter for optional wallet.
     *
     * @param string $wallet
     * @return Client
     */
    public function setwallet($wallet)
    {
        if ($wallet) {
            $this->options[ 'json' ][ 'wallet' ] = $wallet;
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

        $body = $this->getBody($response);

        if (is_object($body) && isset($body->status) && $body->status != 'SUCCESS') {
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
     * @param Response $response
     * @return array
     */
    protected function getBody(Response $response)
    {
        return json_decode((string) $response->getBody());
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
