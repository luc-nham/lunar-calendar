<?php namespace VanTran\LunarCalendar\Tests\Correctors;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Correctors\GregorianToLunarCorrector;
use VanTran\LunarCalendar\Storages\GregorianToLunarStorageMutable;

class GregorianToLunarCorrectorTest extends TestCase
{
    private $timezone;

    public function setup(): void
    {
        $this->timezone = new DateTimeZone('+0700');
    }

    /**
     * Kiểm tra ngày tháng thông thường không nhuận
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testNoLeap(): void
    {
        $corrector = new GregorianToLunarCorrector(
            new GregorianToLunarStorageMutable(
                new DateTime('2023-05-09', $this->timezone)
            )
        );

        $storage = $corrector->getDateTimeStorage();

        // Âm lịch tương ứng: ngày 20 tháng 03 năm 2023
        $this->assertEquals(2023, $storage->getYear());
        $this->assertEquals(3, $storage->getMonth());
        $this->assertEquals(20, $storage->getDay());
        $this->assertFalse($storage->isLeapMonth());
    }

    /**
     * Kiểm tra tháng nhuận
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testHasLeap(): void
    {
        $corrector = new GregorianToLunarCorrector(
            new GregorianToLunarStorageMutable(
                new DateTime('2025-08-13', $this->timezone)
            )
        );

        $storage = $corrector->getDateTimeStorage();

        // Âm lịch tương ứng: ngày 20 tháng 06 (nhuận) năm 2025
        $this->assertEquals(2025, $storage->getYear());
        $this->assertEquals(6, $storage->getMonth());
        $this->assertEquals(20, $storage->getDay());
        $this->assertTrue($storage->isLeapMonth());
    }

    /**
     * Kiểm tra thời điểm Dương lịch đã qua năm mới nhưng Âm lịch vẫn còn trong năm cũ
     * 
     * @return void 
     */
    public function testYearsDissimilarity(): void
    {
        $corrector = new GregorianToLunarCorrector(
            new GregorianToLunarStorageMutable(
                new DateTime('2024-01-26', $this->timezone)
            )
        );

        $storage = $corrector->getDateTimeStorage();

        // Âm lịch tương ứng: ngày 16 tháng 12 năm 2023
        $this->assertEquals(2023, $storage->getYear());
        $this->assertEquals(12, $storage->getMonth());
        $this->assertEquals(16, $storage->getDay());
        $this->assertFalse($storage->isLeapMonth());
    }
}