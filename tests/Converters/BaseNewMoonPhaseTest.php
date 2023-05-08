<?php namespace VanTran\LunarCalendar\Tests\Converters;

use DateTime;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\BaseNewMoonPhaseConverter;
use VanTran\LunarCalendar\Converters\JdnToDateTimeConverter;

class BaseNewMoonPhaseTest extends TestCase
{
    /**
     * Danh sách một số điểm Sóc trong năm 2023
     * @return array 
     */
    public static function listNewMoons(): array
    {
        return [
            [
                'jd' => 2459936.9291239106,
                'cycles' => 1521,
                'date' => '2022-12-23 17:17:56 +07:00'
            ],
            [
                'jd' => 2459966.371877139,
                'cycles' => 1522,
                'date' => '2023-01-22 03:55:30 +07:00'
            ],
            [
                'jd' => 2459995.797975818,
                'cycles' => 1523,
                'date' => '2023-02-20 14:09:05 +07:00'
            ],
            [
                'jd' => 2460025.226907106,
                'cycles' => 1524,
                'date' => '2023-03-22 00:26:44 +07:00'
            ],
            [
                'jd' => 2460054.6776446863,
                'cycles' => 1525,
                'date' => '2023-04-20 11:15:48 +07:00'
            ],
            [
                'jd' => 2460084.1638496844,
                'cycles' => 1526,
                'date' => '2023-05-19 22:55:56 +07:00'
            ],
            [
                'jd' => 2460113.693867649,
                'cycles' => 1527,
                'date' => '2023-06-18 11:39:10 +07:00'
            ],
            [
                'jd' => 2460143.2729884232,
                'cycles' => 1528,
                'date' => '2023-07-18 01:33:06 +07:00'
            ],
            [
                'jd' => 2460172.901959023,
                'cycles' => 1529,
                'date' => '2023-08-16 16:38:49 +07:00'
            ],
            [
                'jd' => 2460202.5695060273,
                'cycles' => 1530,
                'date' => '2023-09-15 08:40:05 +07:00'
            ]
        ];
    }

    /**
     * @dataProvider listNewMoons
     * 
     * @param float $newMoonJd 
     * @param int $cycles 
     * @param string $date 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testNormalMode(float $newMoonJd, int $cycles, string $date): void
    {
        $offset = 25200;
        $type = BaseNewMoonPhaseConverter::NORMAL_MODE;

        $nmC1 = new BaseNewMoonPhaseConverter($newMoonJd, $offset, $type);
        $nmC2 = new BaseNewMoonPhaseConverter($newMoonJd + 10, $offset, $type);

        $this->assertEquals($newMoonJd, $nmC1->getJd());
        $this->assertEquals($newMoonJd, $nmC2->getJd());

        $expectedDate = new DateTime($date);
        $ouputDate = (new JdnToDateTimeConverter($nmC1))->getDateTime();

        $this->assertEquals($expectedDate->format('c'), $ouputDate->format('c'));
    }

    /**
     * @dataProvider listNewMoons
     * 
     * @param float $newMoonJd 
     * @param int $cycles 
     * @param string $date 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testStrictMode(float $newMoonJd, int $cycles, string $date): void
    {
        $offset = 25200;
        $type = BaseNewMoonPhaseConverter::STRICT_MODE;

        $nmConverter = new BaseNewMoonPhaseConverter($newMoonJd, $offset, $type);
        $diff = $newMoonJd - $nmConverter->getJd();

        $this->assertTrue($diff > 29 && $diff < 30);
    }
}