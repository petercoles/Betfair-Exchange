<?php

namespace PeterColes\Tests\Integration\Betting;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class MarketsTest extends BaseTest
{
    public function testListMarketCatalogueNoFiltering()
    {
        $result = Betfair::betting('listMarketCatalogue');

        $this->assertObjectHasAttribute('marketId', $result[ 0 ]);
        $this->assertObjectHasAttribute('marketName', $result[ 0 ]);
    }

    public function testListMarketCatalogueFilterByEventIdOnly()
    {
        // get an event that we can work with
        $events = collect(Betfair::betting('listEvents', ['sortByDesc' => 'marketCount']))->values();

        if (count($events) > 0 && $events[ 0 ]->marketCount > 0) {
            $result = Betfair::betting('listMarketCatalogue', ['filter' => ['eventIds' => [$events[ 0 ]->event->id]]]);

            $this->assertEquals($events[ 0 ]->marketCount, count($result));
        }
    }

    public function testListMarketCatalogueWithParams()
    {
        // get an event that we can work with
        $events = collect(Betfair::betting('listEvents'))->sortByDesc('marketCount')->values();

        if (count($events) > 0 && $events[ 0 ]->marketCount > 0) {
            $result = Betfair::betting('listMarketCatalogue', [
                'filter' => ['eventIds' => [$events[ 0 ]->event->id]],
                'marketProjection' => [ 'COMPETITION', 'EVENT', 'EVENT_TYPE', 'MARKET_START_TIME', 'MARKET_DESCRIPTION', 'RUNNER_DESCRIPTION', 'RUNNER_METADATA' ],
                'sort' => 'MAXIMUM_TRADED',
                'maxResults' => 2,
                'locale' => 'it'
            ]);

            $this->assertEquals(2, count($result));
            $this->assertObjectHasAttribute('marketStartTime', $result[0]);
            $this->assertObjectHasAttribute('description', $result[0]);
            $this->assertObjectHasAttribute('competition', $result[0]);
            $this->assertObjectHasAttribute('eventType', $result[0]);
            $this->assertObjectHasAttribute('event', $result[0]);
            $this->assertObjectHasAttribute('runners', $result[0]);
            $this->assertObjectHasAttribute('metadata', $result[0]->runners[0]);
        }
    }

    public function testListMarketBookWithMarketIdOnly()
    {
        $events = collect(Betfair::betting('listEvents'))->sortByDesc('marketCount')->values();
        $markets = Betfair::betting('listMarketCatalogue', ['filter' => ['eventIds' => [$events[0]->event->id]]]);
        $result = Betfair::betting('listMarketBook', [ 'marketIds' => [$markets[0]->marketId]]);

        $this->assertObjectHasAttribute('numberOfRunners', $result[0]);
        $this->assertObjectHasAttribute('runners', $result[0]);
    }

    public function testListMarketBookWithParameters()
    {
        $events = collect(Betfair::betting('listEvents'))->sortByDesc('marketCount')->values();
        $markets = Betfair::betting('listMarketCatalogue', ['filter' => ['eventIds' => [$events[0]->event->id]]]);
        $result = Betfair::betting('listMarketBook', [
            'marketIds' => [$markets[0]->marketId],
            'priceProjection' => ['priceData' => ['EX_ALL_OFFERS']],
            'orderProjection' => 'ALL',
            'matchProjection' => 'NO_ROLLUP',
            'currencyCode' => 'EUR',
            'locale' => 'it'
        ]);

        $this->assertObjectHasAttribute('ex', $result[0]->runners[0]);
    }  

    public function testListMarketProfitAndLossWithMarketIdOnly()
    {
        $events = collect(Betfair::betting('listEvents'))->sortByDesc('marketCount')->values();
        $markets = Betfair::betting('listMarketCatalogue', ['filter' => ['eventIds' => [$events[0]->event->id]]]);
        $result = Betfair::betting('listMarketProfitAndLoss', ['marketIds' => [$markets[0]->marketId]]);

        // to test this fully would require actual bets to be placed by the account being used for testing
        // but we can test that we connect and get back the right type of object
        $this->assertObjectHasAttribute('profitAndLosses', $result[0]);
    }

    public function testListMarketProfitAndLossWithParameters()
    {
        $events = collect(Betfair::betting('listEvents'))->sortByDesc('marketCount')->values();
        $markets = Betfair::betting('listMarketCatalogue', ['filter' => ['eventIds' => [$events[0]->event->id]]]);
        $result = Betfair::betting('listMarketProfitAndLoss', [
            'marketIds' => [ $markets[0]->marketId ],
            'includeSettledBets' => true,
            'includeBspBets' => true,
            'netOfCommission' => true,
        ]);

        // the parameters will cause this extra attribute to be included, even in an "empty" response object 
        $this->assertObjectHasAttribute('commissionApplied', $result[0]);
    }
}
