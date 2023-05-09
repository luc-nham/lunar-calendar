<?php namespace VanTran\LunarCalendar\Tests\Correctors;

use DateTimeZone;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\GregorianToJDNConverter;
use VanTran\LunarCalendar\Converters\JdnToNewMoonPhaseConverter;
use VanTran\LunarCalendar\Correctors\LunarDateTimeCorrector;
use VanTran\LunarCalendar\Parsers\LunarDateTimeParser;

class LunarDateTimeCorrectorTest extends TestCase
{
    private $timezone;

    public function setup(): void
    {
        $this->timezone = new DateTimeZone('+0700');
    }

    public function testRightLunarDateTime(): void
    {
        $jd = new GregorianToJDNConverter();
        $jd->setDate(2023, 5, 17);
        $jd->setOffset(25200);

        $newMoon = new JdnToNewMoonPhaseConverter($jd);

        $parser = new LunarDateTimeParser('28/3/2023', $this->timezone);
        $corrector = new LunarDateTimeCorrector($parser);

        $this->assertEquals($jd->getMidnightJd(), $corrector->getMidnightJd());
        $this->assertEquals($newMoon->getMidnightJd(), $corrector->getNewMoon()->getMidnightJd());
    }

    /**
     * Kiểm tra khớp dữ liệu nếu Âm lịch đầu vào không chính xác
     * 
     * @return void 
     */
    public function testWrongLunarDateTime(): void
    {
        // Kiểm tra thời gian sai ở tháng thông thường, không nhuận
        $jd = new GregorianToJDNConverter(); // 01/04/2023 Âm lịch
        $jd->setDate(2023, 5, 19);  
        $jd->setOffset(25200);

        $newMoon = new JdnToNewMoonPhaseConverter($jd);
        $parser = new LunarDateTimeParser('30/3/2023', $this->timezone);
        $corrector = new LunarDateTimeCorrector($parser);

        $this->assertEquals($jd->getMidnightJd(), $corrector->getMidnightJd());
        $this->assertEquals($newMoon->getMidnightJd(), $corrector->getNewMoon()->getMidnightJd());

        // Kiểm tra thời gian sai ở tháng kế tháng nhuận
        $jd->setDate(2017, 7, 23); // 01/06+/2071 Âm lịch

        $newMoon = new JdnToNewMoonPhaseConverter($jd);
        $parser = new LunarDateTimeParser('30/06/2017', $this->timezone);
        $corrector = new LunarDateTimeCorrector($parser);
        $storage = $corrector->getDateTimeStorage();

        $this->assertEquals($jd->getMidnightJd(), $corrector->getMidnightJd());
        $this->assertEquals($newMoon->getMidnightJd(), $corrector->getNewMoon()->getMidnightJd());
        $this->assertTrue($storage->isLeapMonth());

        // Kiểm tra thời gian sai ở ngày cuối của năm cũ
        $jd->setDate(2026, 2, 17); // 01/01/2026

        $newMoon = new JdnToNewMoonPhaseConverter($jd);
        $parser = new LunarDateTimeParser('30/12/2025', $this->timezone);
        $corrector = new LunarDateTimeCorrector($parser);
        $storage = $corrector->getDateTimeStorage();

        $this->assertEquals($jd->getMidnightJd(), $corrector->getMidnightJd());
        $this->assertEquals($newMoon->getMidnightJd(), $corrector->getNewMoon()->getMidnightJd());

        $this->assertEquals(2026, $storage->getYear());
        $this->assertEquals(1, $storage->getMonth());
        $this->assertEquals(1, $storage->getDay());
    }

    public function testWrongLeapMonthInput(): void
    {
        // Năm 2023 nhuận tháng 11 chứ không phải tháng 8
        $parser = new LunarDateTimeParser('15/08+/2023', $this->timezone);
        $corrector = new LunarDateTimeCorrector($parser);

        $this->assertFalse($corrector->getDateTimeStorage()->isLeapMonth());
    }
}