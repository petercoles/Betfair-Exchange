<?php

namespace PeterColes\Tests;

use PeterColes\Betfair\Betfair;

class AccountTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();

        Betfair::auth()->init($this->appKey, $this->username, $this->password);
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf('PeterColes\Betfair\Api\Account', Betfair::account());
    }

    public function testGetAccountDetails()
    {
        $result = Betfair::account()->getAccountDetails();

        $this->assertObjectHasAttribute('firstName', $result);
        $this->assertObjectHasAttribute('pointsBalance', $result);
    }

    public function testGetDefaultAccountFunds()
    {
        $result = Betfair::account()->getAccountFunds();

        $this->assertObjectHasAttribute('availableToBetBalance', $result);
        $this->assertEquals('UK', $result->wallet);
    }

    public function testGetAustralianAccountFunds()
    {
        $result = Betfair::account()->getAccountFunds('AUSTRALIAN');

        $this->assertObjectHasAttribute('availableToBetBalance', $result);
        $this->assertEquals('AUSTRALIAN', $result->wallet);
    }
}
