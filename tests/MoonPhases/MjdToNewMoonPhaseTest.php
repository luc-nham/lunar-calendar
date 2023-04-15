<?php namespace VanTran\LunarCalendar\Tests\MoonPhases;

use DateTime;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\GeneratorNotSupportedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Mjd\BaseMjd;
use VanTran\LunarCalendar\Mjd\DateTimeToMjd;
use VanTran\LunarCalendar\MoonPhases\MjdToNewMoonPhase;

class MjdToNewMoonPhaseTest extends TestCase
{
    /**
     * Danh sách một số điểm Sóc trong năm 2023
     * @return array 
     */
    public static function listNewMoons(): array
    {
        return [
            [
                'date' => '2022-12-23',
                'time' => '17:17:56',
                'timezone' => '+0700',
                'jd' => 2459937.4291239106,
                'utc_offset' => 25200,
                'moon_cycles' => 1521
            ],
            [
                'date' => '2023-01-22',
                'time' => '03:55:30',
                'timezone' => '+0700',
                'jd' => 2459966.871877139,
                'utc_offset' => 25200,
                'moon_cycles' => 1522
            ],
            [
                'date' => '2023-02-20',
                'time' => '14:09:05',
                'timezone' => '+0700',
                'jd' => 2459996.297975818,
                'utc_offset' => 25200,
                'moon_cycles' => 1523
            ],
            [
                'date' => '2023-03-22',
                'time' => '00:26:44',
                'timezone' => '+0700',
                'jd' => 2460025.726907106,
                'utc_offset' => 25200,
                'moon_cycles' => 1524
            ],
            [
                'date' => '2023-04-20',
                'time' => '11:15:48',
                'timezone' => '+0700',
                'jd' => 2460055.1776446863,
                'utc_offset' => 25200,
                'moon_cycles' => 1525
            ],
            [
                'date' => '2023-05-19',
                'time' => '22:55:56',
                'timezone' => '+0700',
                'jd' => 2460084.6638496844,
                'utc_offset' => 25200,
                'moon_cycles' => 1526
            ],
            [
                'date' => '2023-06-18',
                'time' => '11:39:10',
                'timezone' => '+0700',
                'jd' => 2460114.193867649,
                'utc_offset' => 25200,
                'moon_cycles' => 1527
            ],
            [
                'date' => '2023-07-18',
                'time' => '01:33:06',
                'timezone' => '+0700',
                'jd' => 2460143.7729884232,
                'utc_offset' => 25200,
                'moon_cycles' => 1528
            ],
            [
                'date' => '2023-08-16',
                'time' => '16:38:49',
                'timezone' => '+0700',
                'jd' => 2460173.401959023,
                'utc_offset' => 25200,
                'moon_cycles' => 1529
            ],
            [
                'date' => '2023-09-15',
                'time' => '08:40:05',
                'timezone' => '+0700',
                'jd' => 2460203.0695060273,
                'utc_offset' => 25200,
                'moon_cycles' => 1530
            ]
        ];
    }

    /**
     * Kiểm tra khởi tạo đối tượng (Test cơ bản, không có dữ liệu đối chiếu)
     * 
     * @return void 
     * @throws ExpectationFailedException 
     * @throws GeneratorNotSupportedException 
     */
    public function testInit(): void
    {
        $mjd = new DateTimeToMjd(new DateTime());
        $newMoon = new MjdToNewMoonPhase($mjd);

        $this->assertNotEmpty($newMoon->getJd());
        $this->assertTrue($newMoon->getJd() <= $mjd->getJd());
        $this->assertEquals($mjd->getOffset(), $newMoon->getOffset());
    }

    /**
     * @dataProvider listNewMoons
     * 
     * @param string $date 
     * @param string $time 
     * @param string $timezone 
     * @param float $jd 
     * @param int $offset 
     * @param int $cycles 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testOuput(string $date, string $time, string $timezone, float $jd, int $offset, int $cycles): void
    {
        $mjd = new BaseMjd(floor($jd), $offset);
        $nmNormalMode = new MjdToNewMoonPhase($mjd, MjdToNewMoonPhase::NORMAL_MODE);
        $nmStrictMode = new MjdToNewMoonPhase($mjd, MjdToNewMoonPhase::STRICT_MODE);

        $this->assertEquals($jd, $nmNormalMode->getJd());
        $this->assertEquals($cycles, $nmNormalMode->getTotalCycles());

        $this->assertTrue($jd - $nmStrictMode->getJd() <= 30);
        $this->assertTrue($cycles - $nmStrictMode->getTotalCycles() == 1);
    }
}