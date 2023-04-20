<?php namespace VanTran\LunarCalendar\Tests\Lunar\VN;

use DateTimeZone;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    private static $timezone;

    /**
     * Múi giờ địa phương cho các bài test Âm lịch Việt Nam
     * 
     * @return DateTimeZone 
     */
    public static function getTimeZone(): DateTimeZone
    {
        if (!self::$timezone) {
            self::$timezone = new DateTimeZone('+0700');
        }

        return self::$timezone;
    }

    final public function testNothing(): void
    {
        $this->assertTrue(true);
    }
}