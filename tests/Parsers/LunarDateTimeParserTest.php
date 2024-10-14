<?php

namespace LucNham\LunarCalendar\Tests\Parsers;

use DateTimeZone;
use LucNham\LunarCalendar\Parsers\LunarDateTimeParser;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarParsingResults;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LunarDateTimeParser::class)]
#[CoversClass(LunarParsingResults::class)]
#[CoversClass(LunarDateTimeInterval::class)]
class LunarDateTimeParserTest extends TestCase
{
    public function testDateTime()
    {
        $results = (new LunarDateTimeParser())->parse('2024-10-20 20:30');
        $lunar = $results->interval;

        $this->assertEquals(20, $lunar->d);
        $this->assertEquals(10, $lunar->m);
        $this->assertEquals(2024, $lunar->y);
        $this->assertEquals(20, $lunar->h);
        $this->assertEquals(30, $lunar->i);
        $this->assertEquals(0, $lunar->s);
        $this->assertEquals(false, $lunar->leap);

        $this->assertEquals(0, $results->warning_count);
        $this->assertEquals(0, $results->error_count);
    }

    public function testDefaultTimeZone()
    {
        $results = (new LunarDateTimeParser())->parse('2024-10-20 20:30');
        $timezone = new DateTimeZone(date_default_timezone_get());

        $this->assertEquals($timezone->getName(), $results->timezone->getName());
    }

    public function testIncludeTimeZone()
    {
        // Timezone without ID
        $results = (new LunarDateTimeParser())->parse('2024-10-20 +0700');

        $this->assertEquals('+07:00', $results->timezone->getName());
        $this->assertEquals(25200, $results->offset);

        $results = (new LunarDateTimeParser())->parse('2024-10-20 -1200');
        $this->assertEquals('-12:00', $results->timezone->getName());

        // Timezone with ID
        $results = (new LunarDateTimeParser())->parse('2024-10-20 Asia/Ho_Chi_Minh');
        $timezone = $results->timezone;

        $this->assertEquals('Asia/Ho_Chi_Minh', $timezone->getName());
        $this->assertEquals(25200, $results->offset);

        $results = (new LunarDateTimeParser())->parse('1945-02-01 Asia/Ho_Chi_Minh');
        $timezone = $results->timezone;

        $this->assertEquals('Asia/Ho_Chi_Minh', $timezone->getName());
        $this->assertEquals(8 * 3600, $results->offset);

        // Time zone included in the input string have higher priority
        $timezone = new DateTimeZone('UTC');
        $results = (new LunarDateTimeParser())->parse('2024-10-20 +0700', $timezone);

        $this->assertEquals('+07:00', $results->timezone->getName());

        // If the time zone is not included in the input string, the time zone parameter will be used
        $results = (new LunarDateTimeParser())->parse('2024-10-20', $timezone);

        $this->assertEquals('UTC', $results->timezone->getName());
    }

    public function testLeapMonthSigns()
    {
        $list = [
            (new LunarDateTimeParser())->parse('2024-05+-11')->interval,
            (new LunarDateTimeParser())->parse('11/05+/2024')->interval,
            (new LunarDateTimeParser())->parse('2024-05-11 00:00 +0700 (+)')->interval,
            (new LunarDateTimeParser())->parse('2024-05-11 [+]')->interval,
        ];

        foreach ($list as $lunar) {
            $this->assertEquals(11, $lunar->d);
            $this->assertEquals(5, $lunar->m);
            $this->assertEquals(2024, $lunar->y);
            $this->assertTrue($lunar->leap);
        }
    }

    public function testIgnoredWarningWhenLunarMonthNumberIs2()
    {
        $reruls = (new LunarDateTimeParser())->parse('2024-02-30');
        $this->assertEquals(0, $reruls->warning_count);
        $this->assertEquals(0, count($reruls->warnings));
    }
}
