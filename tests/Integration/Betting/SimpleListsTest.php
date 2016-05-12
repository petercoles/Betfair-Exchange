<?php

namespace PeterColes\Tests\Integration\Betting;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class SimpleListsTest extends BaseTest
{
    public function testListCompetitions()
    {
        $result = Betfair::betting('listCompetitions');

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('competition', $result[0]);
    }

    public function testListCountries()
    {
        $result = Betfair::betting('listCountries');

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('countryCode', $result[0]);
    }

    public function testListEvents()
    {
        $result = Betfair::betting('listEvents');

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('event', $result[0]);
    }

    public function testListEventsWithTextFilter()
    {
        $result = Betfair::betting('listEvents', ['filter' => ['textQuery' => 'England']]);

        if (count($result)) {
            $this->assertTrue(strpos($result[0]->event->name, 'England') !== 0);
        }
    }

    public function testListEventsWithEventIdsFilter()
    {
        // get some current IDs with which to work
        $firstResult = Betfair::betting('listEvents');
        $eventIds = [$firstResult[0]->event->id, $firstResult[1]->event->id];

        $secondResult = Betfair::betting('listEvents', ['filter' =>['eventIds' => $eventIds]]);
        $secondResultIds = collect($secondResult)->pluck('event.id');

        $this->assertTrue(sizeof($secondResult) == 2);
        $this->assertContains($eventIds[0], $secondResultIds);
        $this->assertContains($eventIds[1], $secondResultIds);
    }

    public function testListEventTypes()
    {
        $result = Betfair::betting('listEventTypes');

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('eventType', $result[0]);
    }

    public function testListMarketTypes()
    {
        $result = Betfair::betting('listMarketTypes');

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('marketType', $result[0]);
    }

    public function testListVenues()
    {
        $result = Betfair::betting('listVenues');

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('venue', $result[0]);
    }
}
