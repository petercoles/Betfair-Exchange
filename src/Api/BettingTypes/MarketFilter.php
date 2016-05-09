<?php

namespace PeterColes\Betfair\Api\BettingTypes;

class MarketFilter
{
    protected $textQuery;

    protected $eventTypeIds;

    protected $eventIds;

    protected $competitionids;

    protected $marketIds;

    protected $venues;

    protected $bpsOnly;

    protected $turnInplayEnabled;

    public function __construct($params)
    {
        foreach ($params as $key => $value) {
            $this->key = $value;
        }
    }
}