<?php

namespace PeterColes\Tests\Integration\Accounts;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class CurrencyRatesTest extends BaseTest
{
   public function testListCurrencyRates()
    {
        $result = Betfair::account('listCurrencyRates');

        $this->assertObjectHasAttribute('currencyCode', $result[ 0 ]);
        $this->assertObjectHasAttribute('rate', $result[ 0 ]);
    }
}
