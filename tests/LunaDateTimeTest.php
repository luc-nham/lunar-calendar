<?php namespace VanTran\LunarCalendar\Tests;

use DateTime;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Throwable;
use VanTran\LunarCalendar\LunarDateTime;

class LunaDateTimeTest extends TestCase
{
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
        $lunar = new LunarDateTime('09/03/2023');
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
        $date = new DateTime('2023-04-28 UTC');
        $lunar = LunarDateTime::createFromDateTime($date);

        $this->assertEquals('09/03/2023', $lunar->format('d/m/Y'));
        $this->assertEquals($date->getTimezone()->getName(), $lunar->getTimezone()->getName());
    }
}