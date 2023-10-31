<?php namespace VanTran\LunarCalendar\Tests;

use DateTime;
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
        $lunar = new LunarDateTime('09/03/2023 +0700');
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

    public function testWithTimeZoneIncluded(): void
    {
        $lunar = new LunarDateTime('01/10/2023 +0700');
        $this->assertNotNull($lunar->getTimezone());
    }

    /**
     * Kiểm tra khởi tạo Âm lịch từ một đối tượng triển khai DateTimeInterface
     * 
     * @return void 
     * @throws Exception 
     * @throws Throwable 
     * @throws ExpectationFailedException 
     */
    public function testCreateFromDateTimeInterface(): void
    {
        $datetime = new DateTime('2023-04-28', new DateTimeZone('UTC'));
        $lunar = LunarDateTime::createFromGregorian($datetime);

        $this->assertEquals('09/03/2023', $lunar->format('d/m/Y'));
        $this->assertEquals('UTC', $lunar->getTimezone()->getName());
    }

    /**
     * Kiểm tra sửa lỗi (được báo cáo) khi chuyển đổi từ Dương lịch sang Âm lịch vào
     * ngày 14 tháng 10 năm 2023, thời gian từ 21 giờ, múi giờ GMT+7
     * 
     * @return void 
     */
    public function testFixErrorOnSpecifiedDate(): void
    {
        $datetime = new DateTime('2023-10-14 21:04:06', new DateTimeZone('+0700'));
        $lunar = LunarDateTime::createFromGregorian($datetime);

        $this->assertEquals('30-08-2023', $lunar->format('d-m-Y'));
    }
}