<?php

namespace PeterColes\Tests;

use PeterColes\Betfair\Api\Auth;
use PeterColes\Betfair\Betfair;

class AuthTest extends BaseTest
{
    public function testAuthClassInstantiation()
    {
        $this->assertInstanceOf('PeterColes\Betfair\Api\Auth', Betfair::auth());
    }

    public function testLoginObtainsSessionToken()
    {
        $token = Betfair::auth()->login($this->appKey, $this->username, $this->password);

        $this->assertTrue(is_string($token));
        $this->assertEquals(44, strlen($token));
    }

    public function testInitLoginsInFirstTimeOnly()
    {
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

        // first init logs in
        $firstSessionToken = Auth::$sessionToken;
        $firstLastLogin = Auth::$lastLogin;
        $this->assertEquals($this->appKey, Auth::$appKey);
        $this->assertTrue(is_string($firstSessionToken));
        $this->assertEquals(44, strlen($firstSessionToken));

        sleep(1); // ensure at least a second between inits to force a different timestamp
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

        //second init should continue to use existing session token
        $secondSessionToken = Auth::$sessionToken;
        $secondLastLogin = Auth::$lastLogin;
        $this->assertEquals($this->appKey, Auth::$appKey);
        $this->assertEquals($firstSessionToken, $secondSessionToken);
        $this->assertNotEquals($firstLastLogin, $secondLastLogin);
    }

    public function testKeepAliveUpdateLastLoginTimestamp()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        $firstSessionToken = Auth::$sessionToken;
        $firstLastLogin = Auth::$lastLogin;

        sleep(1); // delay to force a different timestamp
        $result = Betfair::auth()->keepAlive();

        $secondSessionToken = Auth::$sessionToken;
        $secondLastLogin = Auth::$lastLogin;

        $this->assertGreaterThan($firstLastLogin, $secondLastLogin);
    }

    public function testLogoutClearsLocalAuthData()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout();

        $this->assertNull(Auth::$appKey);
        $this->assertNull(Auth::$sessionToken);
        $this->assertNull(Auth::$lastLogin);
    }

    public function testNoBetfairSessionAfterLogout()
    {
        $token = Betfair::auth()->init($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout();

        // First logout is fine, but the second should throw a NO_SESSION exception.
        // We can't yet tell of that is the exception being thown and
        // need more extensive exception handling (planned) to be sure.
        $this->setExpectedException('Exception');
        Betfair::auth()->logout();
    }

    public function testSessionRemaining()
    {
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

        sleep(2);

        $this->assertEquals(Auth::SESSION_LENGTH - 2, Betfair::auth()->sessionRemaining());
    }
}
