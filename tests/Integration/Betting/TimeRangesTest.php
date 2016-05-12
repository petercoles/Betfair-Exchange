<?php

namespace PeterColes\Tests\Integration\Betting;

use PeterColes\Betfair\Betfair;
use PeterColes\Tests\Integration\BaseTest;

class TimeRangesTest extends BaseTest
{
    public function testListTimeRangesByDay()
    {
        $result = Betfair::betting('listTimeRanges', ['filter' => [ 'textQuery' => 'England' ], 'granularity' => 'DAYS']);
        $day = 24 * 60 * 60;
        $this->assertObjectHasAttribute('timeRange', $result[0]);
        $this->assertEquals($day, strtotime($result[0]->timeRange->to) - strtotime($result[0]->timeRange->from));
    }

    public function testListTimeRangesByHour()
    {
        $result = Betfair::betting('listTimeRanges', ['filter' => [ 'textQuery' => 'England' ], 'granularity' => 'HOURS']);
        $hour = 60 * 60;

        $this->assertObjectHasAttribute('timeRange', $result[0]);
        $this->assertEquals($hour, strtotime($result[0]->timeRange->to) - strtotime($result[0]->timeRange->from));
    }

    public function testListTimeRangesByMinute()
    {
        $result = Betfair::betting('listTimeRanges', ['filter' => [ 'textQuery' => 'England' ], 'granularity' => 'MINUTES']);
        $minute = 60;

        $this->assertObjectHasAttribute('timeRange', $result[0]);
        $this->assertEquals($minute, strtotime($result[0]->timeRange->to) - strtotime($result[0]->timeRange->from));
    }
}
