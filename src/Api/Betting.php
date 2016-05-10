<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Api\BettingTypes\MarketFilter;
use PeterColes\Betfair\Http\Client as HttpClient;

class Betting
{
    const ENDPOINT = 'https://api.betfair.com/exchange/betting/rest/v1.0/';

    protected $httpClient;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    /**
     * Six Exchange methods have an identical API, so we bundle them into a single magic call e.g.
     * @method listCompetitions(string $appKey, string $sessionToken, array $filters, string $locale)
     * @return array
     */
    public function __call($method, $params)
    {
        if (in_array($method, [ 'listCompetitions', 'listCountries', 'listEvents', 'listEventTypes', 'listMarketTypes', 'listVenues' ])) {

            $filters = isset($params[ 2 ]) ? $params[ 2 ] : [ ];
            $locale = isset($params[ 3 ]) ? $params[ 3 ] : [ ];

            return $this->httpClient
                ->setMethod('post')
                ->setEndPoint(self::ENDPOINT.$method.'/')
                ->addHeaders([ 'X-Application' => $params[ 0 ], 'X-Authentication' => $params[ 1 ], 'Content-Type' => 'application/json' ])
                ->setFilter(new MarketFilter($filters))
                ->setLocale($locale)
                ->send();
        }
    }
}
