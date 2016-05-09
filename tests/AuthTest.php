<?php

use PeterColes\Betfair\Betfair;

require '.env.php'; // load authentication credentials

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->appKey = getenv('APP_KEY');
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf('PeterColes\Betfair\Api\Auth\Auth', Betfair::auth());
    }
}
