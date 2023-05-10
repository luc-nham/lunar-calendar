<?php namespace VanTran\LunarCalendar\Tests;

use DateTimeZone;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Throwable;
use VanTran\LunarCalendar\LunarDateTime;

class LunaDateTimeTest extends TestCase
{
    private $timezone;

    public function setup(): void
    {
        $this->timezone = new DateTimeZone('+0700');
    }

    /**
     * Kiểm tra chuyển đổi về Dương lịch
     * 
     * @return void 
     * @throws Exception 
     * @throws Throwable 
     * @throws ExpectationFailedException 
     */
    public function testToDateTime(): void
    {
        $lunar = new LunarDateTime('09/03/2023', $this->timezone);
        $date = $lunar->toDateTime();

        $this->assertEquals('2023-04-28T00:00:00+07:00', $date->format('c'));
    }

    /**
     * Kiểm tra khởi tạo từ đối tượng ngày tháng Dương lịch
     * 
     * @return void 
     * @throws Exception 
     * @throws Throwable 
     * @throws ExpectationFailedException 
     */
    public function testCreateFromDateTime(): void
    {
        $lunar = LunarDateTime::createFromGregorian('2023-04-28 UTC');

        $this->assertEquals('09/03/2023', $lunar->format('d/m/Y'));
        $this->assertEquals('UTC', $lunar->getTimezone()->getName());

        $lunar = new LunarDateTime('2023-04-28', $this->timezone, LunarDateTime::GREGORIAN_INPUT);
        $this->assertEquals('09/03/2023', $lunar->format('d/m/Y'));
    }
}