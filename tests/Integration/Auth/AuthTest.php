<?php

namespace PeterColes\Tests\Integration\Auth;

use PeterColes\Betfair\Api\Auth;
use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

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
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

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
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout();

        $this->assertNull(Auth::$appKey);
        $this->assertNull(Auth::$sessionToken);
        $this->assertNull(Auth::$lastLogin);
    }

    public function testNoBetfairSessionAfterLogout()
    {
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

        Betfair::auth()->logout();

        $this->expectException('Exception');
        Betfair::auth()->logout();
    }

    public function testSessionRemaining()
    {
        Betfair::auth()->init($this->appKey, $this->username, $this->password);

        sleep(2);

        $this->assertEquals(Auth::SESSION_LENGTH - 2, Betfair::auth()->sessionRemaining());
    }
}
