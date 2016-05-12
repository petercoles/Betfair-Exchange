<?php

namespace PeterColes\Tests\Integration\Betting;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class OrdersTest extends BaseTest
{
    public function testListCurrentOrdersWithNoParams()
    {
        $result = Betfair::betting('listCurrentOrders');

        $this->assertObjectHasAttribute('currentOrders', $result);
    }
}
