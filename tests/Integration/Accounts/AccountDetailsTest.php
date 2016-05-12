<?php

namespace PeterColes\Tests\Integration\Accounts;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class AccountDetailsTest extends BaseTest
{
    public function testGetAccountDetails()
    {
        $result = Betfair::account('getAccountDetails');

        $this->assertObjectHasAttribute('firstName', $result);
        $this->assertObjectHasAttribute('pointsBalance', $result);
    }

    public function testAccountStatement()
    {
        $result = Betfair::account('getAccountStatement');

        $this->assertObjectHasAttribute('accountStatement', $result);
    }
}
