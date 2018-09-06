<?php

namespace PeterColes\Tests\Integration;

use PeterColes\Betfair\Betfair;
use PHPUnit\Framework\TestCase;

require '.env.php'; // load authentication credentials

abstract class BaseTest extends TestCase
{
    protected function setUp()
    {
        $this->appKey = getenv('APP_KEY');
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');

        Betfair::init($this->appKey, $this->username, $this->password);
    }
}
