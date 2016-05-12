<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Http\Client as HttpClient;

class Betting
{
    const ENDPOINT = 'https://api.betfair.com/exchange/betting/rest/v1.0/';

    protected $httpClient;

    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpClient;
    }

    public function listMarketcatalogue($filter = [ ], $marketProjection = [ ], $sort = null, $maxResults = 100, $locale = null)
    {
        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'listMarketCatalogue/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setFilter($filter)
            ->setProjection('marketProjection', $marketProjection)
            ->setSort($sort)
            ->setMaxResults($maxResults)
            ->setLocale($locale)
            ->send();
    }

    public function listMarketBook($marketIds = [ ], $priceProjection = null, $orderProjection = null, $matchProjection = null, $currencyCode = null, $locale = null)
    {
        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'listMarketBook/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setMarketIds($marketIds, true)
            ->setProjection('priceProjection', $priceProjection)
            ->setProjection('orderProjection', $orderProjection)
            ->setProjection('matchProjection', $matchProjection)
            ->setCurrencyCode($currencyCode)
            ->setLocale($locale)
            ->send();
    }

    public function listMarketProfitAndLoss($marketIds = [ ], $includeSettledBets = false, $includeBspBets = false, $netOfCommission = false)
    {
        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'listMarketProfitAndLoss/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setMarketIds($marketIds, true)
            ->setFlag('includeSettledBets', $includeSettledBets)
            ->setFlag('includeBspBets', $includeBspBets)
            ->setFlag('netOfCommission', $netOfCommission)
            ->send();
    }

    public function listTimeRanges($marketFilter, $timeGranularity)
    {
        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'listTimeRanges/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setFilter($marketFilter)
            ->setTimeGranularity($timeGranularity)
            ->send();
    }

    public function listCurrentOrders($betIds = null, $marketIds = null, $orderProjection = null, $placedDateRange = null, $dateRange = null, $orderBy = null, $sortDir = null, $fromRecord = null, $recordCount = null)
    {
        return $this->httpClient
            ->setMethod('post')
            ->setEndPoint(self::ENDPOINT.'listCurrentOrders/')
            ->authHeaders()
            ->addHeader([ 'Content-Type' => 'application/json' ])
            ->setBetIds($betIds)
            ->setMarketIds($marketIds)
            ->setProjection('orderProjection' , $orderProjection)
            ->setDateRange('placedDateRange', $placedDateRange)
            ->setDateRange('dateRange', $dateRange)
            ->setOrder($orderBy, $sortDir)
            ->setRecordRange($fromRecord, $recordCount)
            ->send();
    }

    /**
     * Six Exchange methods have an identical API, so we bundle them into a single magic call e.g.
     * @method listCompetitions(array $filters, string $locale)
     * @return array
     */
    public function __call($method, $params)
    {
        if (in_array($method, [ 'listCompetitions', 'listCountries', 'listEvents', 'listEventTypes', 'listMarketTypes', 'listVenues' ])) {

            $filter = isset($params[ 0 ]) ? $params[ 0 ] : [ ];
            $locale = isset($params[ 1 ]) ? $params[ 1 ] : [ ];

            return $this->httpClient
                ->setMethod('post')
                ->setEndPoint(self::ENDPOINT.$method.'/')
                ->authHeaders()
                ->addHeader([ 'Content-Type' => 'application/json' ])
                ->setFilter($filter)
                ->setLocale($locale)
                ->send();
        }
    }
}
