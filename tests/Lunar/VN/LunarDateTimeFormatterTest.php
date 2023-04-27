<?php namespace VanTran\LunarCalendar\Tests\Lunar\VN;

use VanTran\LunarCalendar\Lunar\LunarDateTimeCorrector;
use VanTran\LunarCalendar\Lunar\LunarDateTimeFormatter;
use VanTran\LunarCalendar\Lunar\LunarParser;

class LunarDateTimeFormatterTest extends BaseTest
{
    public function testFormat(): void
    {
        $parser = new LunarParser('1/5+/1952 23:59:59', $this->getTimeZone());
        $corrector = new LunarDateTimeCorrector($parser);
        $formatter = new LunarDateTimeFormatter($corrector);

        $this->assertEquals('1952-05-01', $formatter->format('Y-L-d'));
        $this->assertEquals('1/5/1952 (+)', $formatter->format('j/l/Y C'));
        $this->assertEquals('01/05+/1952 11:59pm', $formatter->format('d/m/Y h:ia'));
    }
}