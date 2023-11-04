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

    /**
     * Kiểm tra sửa lỗi lấy sai số tháng âm lịch trong các năm nhuận khi tháng cần
     * tính chưa đến tháng nhuận
     * 
     * @return void
     */
    public function testFixErrorMonthMumberOnLeapYears(): void
    {
        $lunar = new LunarDateTime('2023-03-17', new DateTimeZone('+0700'), 2);
        $this->assertEquals('26/02/2023', $lunar->format('d/m/Y'));

        $lunar = new LunarDateTime('1990-03-19', new DateTimeZone('+0700'), 2);
        $this->assertEquals('23/02/1990', $lunar->format('d/m/Y'));

        $lunar = new LunarDateTime('1990-07-24', new DateTimeZone('UTC'), 2);
        $this->assertEquals('03/06/1990', $lunar->format('d/m/Y'));
    }

    /**
     * Kiểm tra sửa lỗi tính sai số năm ở các năm nhuận khi thời gian chuyển đổi
     * rơi vào khoảng tháng Giêng âm lịch
     * 
     * @return void
     */
    public function testFixErrorYearNumberOnLeapYears(): void
    {
        $lunar = new LunarDateTime('1990-01-28', new DateTimeZone('+0700'), 2);
        $this->assertEquals('02/01/1990', $lunar->format('d/m/Y'));

        $lunar = new LunarDateTime('1990-02-25', new DateTimeZone('+0700'), 2);
        $this->assertEquals('01/02/1990', $lunar->format('d/m/Y'));

        $lunar = new LunarDateTime('1990-01-19', new DateTimeZone('+0700'), 2);
        $this->assertEquals('23/12/1989', $lunar->format('d/m/Y'));
    }

    /**
     * Kiểm tra sửa lỗi tính toán sai tổng số ngày trong tháng trong trường hợp
     * đầu vào là một chuỗi Âm lịch sai.
     * @return void
     */
    public function testFixErrorTotalDayOfMonth(): void
    {
        // Âm lịch đầu vào sai, tháng 09/2023 chỉ có 29 ngày (múi giờ GMT+7).
        $lunar = new LunarDateTime('30/09/2023 +0700');
        
        $this->assertEquals('01/10', $lunar->format('d/m'));
        $this->assertEquals('30', $lunar->format('t'));

        // Dương lịch tương ứng với ngày 01/10/2023 âm lịch
        $lunar = new LunarDateTime('2023/11/13 +0700', null, 2);
        $this->assertEquals('01/10', $lunar->format('d/m'));
        $this->assertEquals('30', $lunar->format('t'));
    }
}