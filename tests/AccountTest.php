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
}
