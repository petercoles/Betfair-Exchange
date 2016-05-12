<?php

namespace PeterColes\Tests\Integration\Accounts;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class DeveloperAppKeysTest extends BaseTest
{
    public function testAccountStatement()
    {
        $result = Betfair::account('getDeveloperAppKeys');

        $this->assertTrue(is_array($result));
        $this->assertObjectHasAttribute('appVersions', $result[0]);
        $this->assertEquals(2, sizeof($result[0]->appVersions));
    }
}
