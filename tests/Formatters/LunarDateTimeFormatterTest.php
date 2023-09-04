<?php namespace VanTran\LunarCalendar\Tests\Formatters;

use DateTime;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Correctors\GregorianToLunarCorrector;
use VanTran\LunarCalendar\Correctors\LunarDateTimeCorrector;
use VanTran\LunarCalendar\Formatters\LunarDateTimeFormatter;
use VanTran\LunarCalendar\Parsers\LunarDateTimeParser;
use VanTran\LunarCalendar\Storages\GregorianToLunarStorageMutable;

class LunarDateTimeFormatterTest extends TestCase
{
    public function testNoLeapMonth(): void
    {
        $formatter = new LunarDateTimeFormatter(
            new LunarDateTimeCorrector(
                new LunarDateTimeParser('21/03/2023 19:00 +0700')
            )
        );

        $this->assertEquals('2023-3-21 07:00pm', $formatter->format('Y-l-d h:ia'));
        $this->assertEquals('21/03/2023 19:00 +07:00', $formatter->format('d/m/Y H:i P'));

        // 2023 is leap year
        $this->assertNotEquals(0, $formatter->format('c'));

        // 2026 is not a leap year
        $formatter = new LunarDateTimeFormatter(
            new LunarDateTimeCorrector(
                new LunarDateTimeParser('21/10/2026 19:00 +0700')
            )
        );

        $this->assertEquals(0, $formatter->format('c'));
    }

    public function testHasLeapMonth(): void
    {
        $formatter = new LunarDateTimeFormatter(
            new GregorianToLunarCorrector(
                new GregorianToLunarStorageMutable(
                    new DateTime('2023-04-19 00:00 +0700')
                )
            )
        );

        $this->assertEquals('29/02/2023 00:00am', $formatter->format('d/L/Y h:ia'));
        $this->assertEquals('29/02+/2023', $formatter->format('d/m/Y'));
        $this->assertEquals('29/2/2023 (+)', $formatter->format('d/l/Y C'));
    }
}