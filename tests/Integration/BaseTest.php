<?php

namespace PeterColes\Tests\Integration;

use PeterColes\Betfair\Betfair;

require '.env.php'; // load authentication credentials

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->appKey = getenv('APP_KEY');
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');

        Betfair::auth()->init($this->appKey, $this->username, $this->password);
    }
}
