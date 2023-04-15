<?php namespace VanTran\LunarCalendar\Tests\Mjd;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Mjd\GregorianToMjd;

class GregorianToMjdTest extends TestCase
{
    /**
     * Danh sách một số điểm MJD
     * @return array 
     */
    public static function mjdDataList(): array
    {
        return [
            [
                'date' => '1960-02-20',
                'time' => '00:52:06',
                'timezone' => '+07:00',
                'jd' => 2436984.744513889,
                'midnight_jd' => 2436984.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1956-07-06',
                'time' => '21:34:32',
                'timezone' => '+07:00',
                'jd' => 2435661.607314815,
                'midnight_jd' => 2435660.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '2026-02-13',
                'time' => '13:32:44',
                'timezone' => '+07:00',
                'jd' => 2461085.2727314816,
                'midnight_jd' => 2461084.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1992-09-27',
                'time' => '02:17:24',
                'timezone' => '+07:00',
                'jd' => 2448892.80375,
                'midnight_jd' => 2448892.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '2009-02-12',
                'time' => '10:45:14',
                'timezone' => '+07:00',
                'jd' => 2454875.156412037,
                'midnight_jd' => 2454874.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1915-01-21',
                'time' => '19:35:00',
                'timezone' => '+07:00',
                'jd' => 2420519.5243055555,
                'midnight_jd' => 2420518.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1911-08-21',
                'time' => '21:48:49',
                'timezone' => '+07:00',
                'jd' => 2419270.6172337965,
                'midnight_jd' => 2419269.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1999-07-13',
                'time' => '04:31:45',
                'timezone' => '+07:00',
                'jd' => 2451372.897048611,
                'midnight_jd' => 2451372.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1918-10-06',
                'time' => '20:53:05',
                'timezone' => '+07:00',
                'jd' => 2421873.5785300927,
                'midnight_jd' => 2421872.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1959-12-07',
                'time' => '13:52:47',
                'timezone' => '+07:00',
                'jd' => 2436910.2866550926,
                'midnight_jd' => 2436909.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '2030-12-05',
                'time' => '08:07:17',
                'timezone' => '+07:00',
                'jd' => 2462841.046724537,
                'midnight_jd' => 2462840.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '2001-01-17',
                'time' => '14:10:41',
                'timezone' => '+07:00',
                'jd' => 2451927.2990856483,
                'midnight_jd' => 2451926.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1979-12-06',
                'time' => '05:48:10',
                'timezone' => '+07:00',
                'jd' => 2444213.9501157408,
                'midnight_jd' => 2444213.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1959-05-18',
                'time' => '12:45:05',
                'timezone' => '+07:00',
                'jd' => 2436707.2396412035,
                'midnight_jd' => 2436706.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1926-01-31',
                'time' => '13:03:03',
                'timezone' => '+07:00',
                'jd' => 2424547.2521180557,
                'midnight_jd' => 2424546.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1900-10-25',
                'time' => '12:45:26',
                'timezone' => '+07:00',
                'jd' => 2415318.239884259,
                'midnight_jd' => 2415317.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1982-03-15',
                'time' => '13:40:08',
                'timezone' => '+07:00',
                'jd' => 2445044.2778703705,
                'midnight_jd' => 2445043.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1950-10-30',
                'time' => '10:53:59',
                'timezone' => '+07:00',
                'jd' => 2433585.162488426,
                'midnight_jd' => 2433584.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1926-12-01',
                'time' => '11:39:53',
                'timezone' => '+07:00',
                'jd' => 2424851.194363426,
                'midnight_jd' => 2424850.7083333335,
                'utc_offset' => 25200
            ],
            [
                'date' => '1934-11-02',
                'time' => '12:38:45',
                'timezone' => '+07:00',
                'jd' => 2427744.2352430555,
                'midnight_jd' => 2427743.7083333335,
                'utc_offset' => 25200
            ]
        ];
    }

    /**
     * @dataProvider mjdDataList
     * 
     * @param string $date 
     * @param string $time 
     * @param float $jd 
     * @param float $midnightJd 
     * @param int $offset 
     * @return void 
     */
    public function testOuput(string $date, string $time, string $timezone, float $jd, float $midnightJd, int $offset): void
    {
        $date = explode('-', $date);
        $time = explode(':', $time);
        $mjd = new GregorianToMjd(
            $offset, 
            $date[0], 
            $date[1],
            $date[2],
            $time[0],
            $time[1],
            $time[2]
        );

        $this->assertEquals(round($jd, 8), round($mjd->getJd(), 8));
        $this->assertEquals($midnightJd, $mjd->getMidnightJd());
        $this->assertEquals($offset, $mjd->getOffset());
    }
}