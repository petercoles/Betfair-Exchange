<?php

namespace PeterColes\Tests;

use PeterColes\Betfair\Betfair;

class BettingTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();

        Betfair::auth()->init($this->appKey, $this->username, $this->password);
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf('PeterColes\Betfair\Api\Betting', Betfair::betting());
    }

    public function testListCompetitions()
    {
        $result = Betfair::betting()->listCompetitions();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('competition', $result[0]);
    }

    public function testListCountries()
    {
        $result = Betfair::betting()->listCountries();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('countryCode', $result[0]);
    }

    public function testListEvents()
    {
        $result = Betfair::betting()->listEvents();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('event', $result[0]);
    }

    public function testListEventsWithTextFilter()
    {
        $result = Betfair::betting()->listEvents(['textQuery' => 'England']);

        if (count($result)) {
            $this->assertTrue(strpos($result[0]->event->name, 'England') !== 0);
        }
    }

    public function testListEventsWithEventIdsFilter()
    {
        // get some current IDs with which to work
        $firstResult = Betfair::betting()->listEvents();
        $eventIds = [$firstResult[0]->event->id, $firstResult[1]->event->id];

        $secondResult = Betfair::betting()->listEvents(['eventIds' => $eventIds]);
        $secondResultIds = collect($secondResult)->pluck('event.id');

        $this->assertTrue(sizeof($secondResult) == 2);
        $this->assertContains($eventIds[0], $secondResultIds);
        $this->assertContains($eventIds[1], $secondResultIds);
    }

    public function testListEventTypes()
    {
        $result = Betfair::betting()->listEventTypes();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('eventType', $result[0]);
    }

    public function testListMarketTypes()
    {
        $result = Betfair::betting()->listMarketTypes();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('marketType', $result[0]);
    }

    public function testListVenues()
    {
        $result = Betfair::betting()->listVenues();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('venue', $result[0]);
    }

    public function testListMarketCatalogueFilterByEventIdOnly()
    {
        // get an event that we can work with
        $events = collect(Betfair::betting()->listEvents())->sortByDesc('marketCount')->values();

        if (count($events) > 0 && $events[0]->marketCount > 0) {
            $result = Betfair::betting()->listMarketCatalogue(['eventIds' => [$events[0]->event->id]]);

            $this->assertEquals($events[0]->marketCount, count($result));
            $this->assertObjectHasAttribute('marketId', $result[0]);
            $this->assertObjectHasAttribute('marketName', $result[0]);
        }
    }

    public function testListMarketCatalogueWithParams()
    {
        // get an event that we can work with
        $events = collect(Betfair::betting()->listEvents())->sortByDesc('marketCount')->values();

        if (count($events) > 0 && $events[0]->marketCount > 0) {
            $eventId = $events[0]->event->id;
            $marketProjection = [ 'COMPETITION', 'EVENT', 'EVENT_TYPE', 'MARKET_START_TIME', 'MARKET_DESCRIPTION', 'RUNNER_DESCRIPTION', 'RUNNER_METADATA' ];
            $sort = 'MAXIMUM_TRADED';
            $maxResults = 2;
            $locale = 'it';
            $result = Betfair::betting()->listMarketCatalogue(['eventIds' => [$eventId]], $marketProjection, $sort, $maxResults, $locale);

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
        $events = collect(Betfair::betting()->listEvents())->sortByDesc('marketCount')->values();
        $markets = Betfair::betting()->listMarketCatalogue(['eventIds' => [$events[0]->event->id]]);
        $result = Betfair::betting()->listMarketBook([$markets[0]->marketId]);

        $this->assertObjectHasAttribute('numberOfRunners', $result[0]);
        $this->assertObjectHasAttribute('runners', $result[0]);
    }

    public function testListMarketBookWithParameters()
    {
        $events = collect(Betfair::betting()->listEvents())->sortByDesc('marketCount')->values();
        $markets = Betfair::betting()->listMarketCatalogue(['eventIds' => [$events[0]->event->id]]);
        $result = Betfair::betting()->listMarketBook(
            [$markets[0]->marketId],   // $marketIds
            ['priceData' => ['EX_ALL_OFFERS']], // $priceProjection (end)        
            'ALL',                     // $orderProjection
            'NO_ROLLUP',               //$matchProjection
            'EUR',                     // $currencyCode,
            'it'                       // $locale
        );

        $this->assertObjectHasAttribute('lastPriceTraded', $result[0]->runners[0]);
        $this->assertObjectHasAttribute('ex', $result[0]->runners[0]);
    }  

    public function testListMarketProfitAndLossWithMarketIdOnly()
    {
        $events = collect(Betfair::betting()->listEvents())->sortByDesc('marketCount')->values();
        $markets = Betfair::betting()->listMarketCatalogue(['eventIds' => [$events[0]->event->id]]);
        $result = Betfair::betting()->listMarketProfitAndLoss([ $markets[0]->marketId ]);

        // to test this properly would require actual bets to be placed
        // for the moment we'll ensure that the request doesn't fail
        // even if it simply returns an empty array as the result
        $this->addToAssertionCount(1);
    }

    public function testListMarketProfitAndLossWithParameters()
    {
        $events = collect(Betfair::betting()->listEvents())->sortByDesc('marketCount')->values();
        $markets = Betfair::betting()->listMarketCatalogue(['eventIds' => [$events[0]->event->id]]);
        $result = Betfair::betting()->listMarketProfitAndLoss([ $markets[0]->marketId ], true, true, true);

        // to test this properly would require actual bets to be placed
        // for the moment we'll ensure that the request doesn't fail
        // even if it simply returns an empty array as the result
        $this->addToAssertionCount(1);
    }
}
