<?php

namespace PeterColes\Betfair\Api;

use PeterColes\Betfair\Api\BaseApi;

class Betting extends BaseApi
{
    /**
     * Betfair API endpoint for betting subsystem requests
     */
    const ENDPOINT = 'https://api.betfair.com/exchange/betting/rest/v1.0/';

    /**
     * Prepare parameters for API requests, ensuring the mandatory requirments are satisfied
     *
     * @param array $params
     */
    public function prepare($params)
    {
        $this->params = !empty($params) ? $params[ 0 ] : [ ];

        // force mandatory fields
        $this->filter();
        $this->maxRecords();
    }

    /**
     * Ensure that a filter parameter is passed where mandatory
     */
    protected function filter()
    {
        $lists = [
            'listCompetitions',
            'listCountries',
            'listEvents',
            'listEventTypes',
            'listMarketTypes',
            'listVenues',
            'listMarketCatalogue'
        ];

        if (in_array($this->method, $lists) && empty($this->params[ 'filter' ])) {
            $this->params['filter'] = new \stdClass;
        }
    }

    /**
     * Ensure that a maxRecord parameter is passed where mandatory
     */
    protected function maxRecords()
    {
        if ($this->method == 'listMarketCatalogue' && empty($this->params[ 'maxResults' ])) {
            $this->params[ 'maxResults' ] = 1000;
        }
    }
}
