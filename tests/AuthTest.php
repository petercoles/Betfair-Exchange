<?php

namespace PeterColes\Tests;

use PeterColes\Betfair\Api\Auth;
use PeterColes\Betfair\Betfair;

class AuthTest extends BaseTest
{
    public function testInstantiation()
    {
        $this->assertInstanceOf('PeterColes\Betfair\Api\Auth', Betfair::auth());
    }

    public function testLogin()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $this->assertTrue(is_string($token));
        $this->assertEquals(44, strlen($token));
    }

    public function testInit()
    {
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $this->assertEquals($this->appKey, Auth::$appKey);
        $this->assertTrue(is_string(Auth::$sessionToken));
        $this->assertEquals(44, strlen(Auth::$sessionToken));
    }

    public function testKeepAlive()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $result = Betfair::auth()->keepAlive();
        $result = Betfair::auth()->keepAlive();
        $result = Betfair::auth()->keepAlive();

        // Test confirms that all keep alive requests received SUCCESS responses,
        // otherwise an exception would have been thrown.
        $this->addToAssertionCount(1);
    }

    public function testLogout()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout();

        // Test simply confirms that logout didn't fail.
        $this->addToAssertionCount(1);
    }

    public function testNoSessionAfterLogout()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout();

        // First logout is fine, but the second should throw a NO_SESSION exception.
        // We can't yet tell of that is the exception being thown and
        // need more extensive exception handling (planned) to be sure.
        $this->setExpectedException('Exception');
        Betfair::auth()->logout();
    }
}
