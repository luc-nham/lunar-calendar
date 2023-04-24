<?php namespace VanTran\LunarCalendar\Tests\Lunar\VN;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\ExpectationFailedException;
use VanTran\LunarCalendar\Lunar\LunarDateTimeCorrector;
use VanTran\LunarCalendar\Lunar\LunarLeapMonth;
use VanTran\LunarCalendar\Lunar\LunarParser;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhase;

class LunarDateTimeCorrectorTest extends BaseTest
{
    /**
     * Kiểm tra điểm Sóc tháng 11 Âm lịch của các năm trong thế kỷ 20 và 21
     * @param int|string $lunarYear 
     * @param string $date 
     * @param float $midnightJd 
     * @return void 
     * @throws ExpectationFailedException 
     */
    #[DataProviderExternal(Lunar11thNewMoonProvider::class, 'listOf20thCentury')]
    #[DataProviderExternal(Lunar11thNewMoonProvider::class, 'listOf21thCentury')]
    public function test11thNewMoons(int|string $lunarYear, string $date, float $midnightJd): void
    {
        $day = rand(1, 30);
        $month = rand(1, 12);

        $parser = new LunarParser("$day/$month/$lunarYear", self::getTimeZone());
        $corrector = new LunarDateTimeCorrector($parser);
        $nm = $corrector->get11thNewMoon();

        $this->assertEquals($midnightJd, $nm->getMidnightJd());
    }

    #[DataProviderExternal(LunarLeapMonthProvider::class, 'listOf20thCentury')]
    #[DataProviderExternal(LunarLeapMonthProvider::class, 'listOf21thCentury')]
    public function testLeapMonth($lunarYear, $leapMonth, $newMoonDate, $timestamp, $midnightJd): void
    {
        $day = rand(1, 30);
        $month = rand(1, 12);

        $parser = new LunarParser("$day/$month/$lunarYear", self::getTimeZone());
        $corrector = new LunarDateTimeCorrector($parser);
        $leap = $corrector->getLeapMonth();

        $this->assertEquals($leapMonth, $leap->getMonth(), sprintf(
            "Year expected: %d",
            $lunarYear
        ));
        $this->assertEquals($midnightJd, $leap->getMidnightJd());
    }
}