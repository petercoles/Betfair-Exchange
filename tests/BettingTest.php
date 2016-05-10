<?php

namespace PeterColes\Tests;

use PeterColes\Betfair\Betfair;

class BettingTest extends BaseTest
{
    public function testInstantiation()
    {
        $this->assertInstanceOf('PeterColes\Betfair\Api\Betting', Betfair::betting());
    }

    public function testListCompetitions()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listCompetitions();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('competition', $result[0]);
    }

    public function testListCountries()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listCountries();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('countryCode', $result[0]);
    }

    public function testListEvents()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listEvents();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('event', $result[0]);
    }

    public function testListEventsWithTextFilter()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listEvents(['textQuery' => 'England']);

        if (count($result)) {
            $this->assertTrue(strpos($result[0]->event->name, 'England') !== 0);
        }
    }

    public function testListEventsWithEventIdsFilter()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

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
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listEventTypes();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('eventType', $result[0]);
    }

    public function testListMarketTypes()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listMarketTypes();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('marketType', $result[0]);
    }

    public function testListVenues()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listVenues();

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('venue', $result[0]);
    }
}
