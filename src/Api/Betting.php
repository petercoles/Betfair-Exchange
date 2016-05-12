<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Api\BaseApi;

class Betting extends BaseApi
{
    const ENDPOINT = 'https://api.betfair.com/exchange/betting/rest/v1.0/';

    public function prepare($params)
    {
        $params = !empty($params) ? $params[ 0 ] : [ ];

        $lists = [ 'listCompetitions', 'listCountries', 'listEvents', 'listEventTypes', 'listMarketTypes', 'listVenues', 'listMarketCatalogue' ];
        if (in_array($this->method, $lists) && empty($params[ 'filter' ])) {
            $params['filter'] = new \stdClass;
        }

        if ($this->method == 'listMarketCatalogue' && empty($params[ 'maxResults' ])) {
            $params[ 'maxResults' ] = 1000;
        }

        return $params;
    }
}
