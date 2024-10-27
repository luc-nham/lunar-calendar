<?php

namespace LucNham\LunarCalenda\Tests\Formatters;

use DateTimeZone;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Formatters\LunarDateTimeDefaultFormatter;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LunarDateTimeDefaultFormatter::class)]
#[CoversClass(LunarDateTimeGuaranteed::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(NewMoonIterator::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(JdToMidnightJd::class)]
class LunarDateTimeDefaultFormatterTest extends TestCase
{
    public function testFormating()
    {
        $interval = new LunarDateTimeGuaranteed(
            d: 1,
            m: 2,
            y: 2024,
            h: 3,
            i: 4,
            s: 5,
            l: 0,
            leap: false,
            j: 2460379.919502,
        );

        $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $offset = 25200;
        $formatter = new LunarDateTimeDefaultFormatter($interval, $timezone, $offset);

        $this->assertEquals('1', $formatter->format('j'));
        $this->assertEquals('01', $formatter->format('d'));

        $this->assertEquals('2', $formatter->format('n'));
        $this->assertEquals('02', $formatter->format('m'));

        $this->assertEquals('2', $formatter->format('l'));
        $this->assertEquals('02', $formatter->format('L'));

        $this->assertEquals('2024', $formatter->format('Y'));

        $this->assertEquals('30', $formatter->format('t'));

        $this->assertEquals('3', $formatter->format('g'));
        $this->assertEquals('3', $formatter->format('G'));

        $this->assertEquals('03', $formatter->format('h'));
        $this->assertEquals('03', $formatter->format('H'));

        $this->assertEquals('04', $formatter->format('i'));

        $this->assertEquals('05', $formatter->format('s'));

        $this->assertEquals('am', $formatter->format('a'));
        $this->assertEquals('AM', $formatter->format('A'));

        $this->assertEquals('+07:00', $formatter->format('P'));
        $this->assertEquals('+0700', $formatter->format('O'));

        $this->assertEquals('1710065044', $formatter->format('U'));
        $this->assertEquals('25200', $formatter->format('Z'));

        $this->assertEquals('', $formatter->format('K'));
        $this->assertEquals('', $formatter->format('k'));

        $this->assertEquals('Asia/Ho_Chi_Minh', $formatter->format('e'));

        $this->assertEquals('2024-02-01T03:04:05+07:00', $formatter->format('c'));

        $this->assertEquals('2024-02-01 03:04:05 +07:00', $formatter->format('Y-m-d H:i:s P k'));
    }

    public function testLeapSign()
    {
        $interval = new LunarDateTimeGuaranteed(
            d: 10,
            m: 11,
            y: 2033,
            h: 10,
            i: 20,
            s: 30,
            l: 11,
            leap: true,
            j: 2463963.222569,
        );

        $timezone = new DateTimeZone('+0700');
        $offset = 25200;
        $formatter = new LunarDateTimeDefaultFormatter($interval, $timezone, $offset);

        $this->assertEquals('(+)', $formatter->format('k'));
        $this->assertEquals('[+]', $formatter->format('K'));

        $this->assertEquals('11+', $formatter->format('l'));
        $this->assertEquals('11+', $formatter->format('L'));

        $this->assertEquals('10/11+/2033', $formatter->format('d/L/Y'));
        $this->assertEquals('2033-11-10T10:20:30+07:00(+)', $formatter->format('c'));

        $this->assertEquals('2033-11-10 10:20 (+)', $formatter->format('Y-m-d H:i k'));
        $this->assertEquals('2033-11-10 10:20 [+]', $formatter->format('Y-m-d H:i K'));
    }

    public function testWestLocations()
    {
        $interval = new LunarDateTimeGuaranteed(
            d: 1,
            m: 2,
            y: 2024,
            h: 3,
            i: 4,
            s: 5,
            l: 0,
            leap: false,
            j: 2460379.919502,
        );

        $timezone = new DateTimeZone('-04:00');
        $offset = -4 * 3600;
        $formatter = new LunarDateTimeDefaultFormatter($interval, $timezone, $offset);

        $this->assertEquals('-04:00', $formatter->format('P'));
        $this->assertEquals('-0400', $formatter->format('O'));
    }
}
