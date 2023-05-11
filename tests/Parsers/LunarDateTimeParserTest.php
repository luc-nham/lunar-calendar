<?php namespace VanTran\LunarCalendar\Tests\Parsers;

use DateTimeZone;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Parsers\LunarDateTimeParser;

class LunarDateTimeParserTest extends TestCase
{
    /**
     * Kiểm tra phân tích ngày tháng
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testDateTimeParsing(): void
    {
        $parser = new LunarDateTimeParser('2022-10-20 15:30'); // Y-m-d H:i:s format
        $this->assertEquals(2022, $parser->getYear());
        $this->assertEquals(10, $parser->getMonth());
        $this->assertEquals(20, $parser->getDay());
        $this->assertEquals(15, $parser->getHour());
        $this->assertEquals(30, $parser->getMinute());
        $this->assertEquals(0, $parser->getSecond());

        $parser = new LunarDateTimeParser('3/8+/1990'); // Định dạng j/n/Y (ngày/tháng/năm)
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
        $parser = new LunarDateTimeParser('06/09/2000'); // UTC
        $tz = $parser->getTimeZone();
        $this->assertEquals('+00:00', $tz->getName());
        $this->assertEquals(0, $parser->getOffset());

        $parser = new LunarDateTimeParser('29/05+/1983T00:00:00+0700'); // Múi giờ Việt Nam
        $tz = $parser->getTimeZone();
        $this->assertEquals('+07:00', $tz->getName());
        $this->assertEquals(25200, $parser->getOffset());

        $parser = new LunarDateTimeParser('1-1-2001 Asia/Ho_Chi_Minh'); // Sử dụng ID múi giờ
        $tz = $parser->getTimeZone();
        $this->assertEquals('Asia/Ho_Chi_Minh', $tz->getName());
        $this->assertEquals(25200, $parser->getOffset());

        $parser = new LunarDateTimeParser('1990-08-20 Asia/Ho_Chi_Minh', new DateTimeZone('UTC')); // Ghi đè múi giờ
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
        $t1 = new LunarDateTimeParser('10/08+/2020');
        $t2 = new LunarDateTimeParser('20-03-1990 (+)');
        $t3 = new LunarDateTimeParser('2020-01-05 +0700'); // Không nhuận

        $this->assertTrue($t1->isLeapMonth());
        $this->assertTrue($t2->isLeapMonth());
        $this->assertFalse($t3->isLeapMonth());
    }
}