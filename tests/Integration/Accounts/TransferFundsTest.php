<?php

namespace PeterColes\Tests\Integration\Accounts;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class TransferFundsTest extends BaseTest
{
    public function testTransferFundsFromUkToAustralia()
    {
        $ukAccountFunds = Betfair::account('getAccountFunds')->availableToBetBalance;
        $ausAccountFunds = Betfair::account('getAccountFunds', ['wallet' => 'AUSTRALIAN'])->availableToBetBalance;

        if ($ukAccountFunds >= 1) {

            Betfair::account('transferFunds', ['from' => 'UK', 'to' => 'AUSTRALIAN', 'amount' => 0.99]);

            $this->assertEquals($ukAccountFunds - 0.99, Betfair::account('getAccountFunds')->availableToBetBalance);
            $this->assertEquals($ausAccountFunds + 0.99, Betfair::account('getAccountFunds', ['wallet' => 'AUSTRALIAN'])->availableToBetBalance, '', 0.01);
        }
    }

    public function testGetAustralianAccountFunds()
    {
        $ukAccountFunds = Betfair::account('getAccountFunds')->availableToBetBalance;
        $ausAccountFunds = Betfair::account('getAccountFunds', ['wallet' => 'AUSTRALIAN'])->availableToBetBalance;

        if ($ausAccountFunds >= 1) {

            Betfair::account('transferFunds', ['from' => 'AUSTRALIAN', 'to' => 'UK', 'amount' => 0.99]);

            $this->assertEquals($ukAccountFunds + 0.99, Betfair::account('getAccountFunds')->availableToBetBalance);
            $this->assertEquals($ausAccountFunds - 0.99, Betfair::account('getAccountFunds', ['wallet' => 'AUSTRALIAN'])->availableToBetBalance, '', 0.01);
        }
    }
}
