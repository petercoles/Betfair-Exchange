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
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listCompetitions($this->appKey, $token);

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('competition', $result[0]);
    }

    public function testListCountries()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listCountries($this->appKey, $token);

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('countryCode', $result[0]);
    }

    public function testListEvents()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listEvents($this->appKey, $token);

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('event', $result[0]);
    }

    public function testListEventTypes()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listEventTypes($this->appKey, $token);

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('eventType', $result[0]);
    }

    public function testListMarketTypes()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listMarketTypes($this->appKey, $token);

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('marketType', $result[0]);
    }

    public function testListVenues()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $result = Betfair::betting()->listVenues($this->appKey, $token);

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('venue', $result[0]);
    }
}
