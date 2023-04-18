<?php namespace VanTran\LunarCalendar\Lunar;

use DateTimeZone;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class LunarParserTest extends TestCase
{
    /**
     * Kiểm tra phân tích ngày tháng
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testDateTimeParsing(): void
    {
        $parser = new LunarParser('2022-10-20 15:30'); // Y-m-d H:i:s format
        $this->assertEquals(2022, $parser->getYear());
        $this->assertEquals(10, $parser->getMonth());
        $this->assertEquals(20, $parser->getDay());
        $this->assertEquals(15, $parser->getHour());
        $this->assertEquals(30, $parser->getMinute());
        $this->assertEquals(0, $parser->getSecond());

        $parser = new LunarParser('3/8+/1990'); // Định dạng j/n/Y (ngày/tháng/năm)
        $this->assertEquals(1990, $parser->getYear());
        $this->assertEquals(8, $parser->getMonth());
        $this->assertEquals(3, $parser->getDay());
        $this->assertTrue($parser->isLeapMonth());
    }

    /**
     * Kiểm tra phân tích múi giờ địa phương và phần bù UTC
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testZoneParsing(): void
    {
        $parser = new LunarParser('06/09/2000'); // Sử dụng múi giờ mặc định +07:00
        $tz = $parser->getTimeZone();
        $this->assertEquals('+07:00', $tz->getName());
        $this->assertEquals(7 * 3600, $parser->getOffset());

        $parser = new LunarParser('29/05+/1983T00:00:00+0600'); // Múi giờ +06:00
        $tz = $parser->getTimeZone();
        $this->assertEquals('+06:00', $tz->getName());
        $this->assertEquals(6 * 3600, $parser->getOffset());

        $parser = new LunarParser('1-1-2001 Asia/Ho_Chi_Minh'); // Sử dụng ID múi giờ
        $tz = $parser->getTimeZone();
        $this->assertEquals('Asia/Ho_Chi_Minh', $tz->getName());
        $this->assertEquals(25200, $parser->getOffset());

        $parser = new LunarParser('1990-08-20 Asia/Ho_Chi_Minh', new DateTimeZone('UTC')); // Ghi đè múi giờ
        $tz = $parser->getTimeZone();
        $this->assertEquals('UTC', $tz->getName());
        $this->assertEquals(0, $parser->getOffset());
    }

    /**
     * Kiểm tra chuỗi thời gian Âm lịch có chứa thành phần xác định tháng nhuận
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testLeapMonthIncluding(): void
    {
        $t1 = new LunarParser('10/08+/2020');
        $t2 = new LunarParser('20-03-1990 (+)');
        $t3 = new LunarParser('2020-01-05 +0700'); // Không nhuận

        $this->assertTrue($t1->isLeapMonth());
        $this->assertTrue($t2->isLeapMonth());
        $this->assertFalse($t3->isLeapMonth());
    }
}