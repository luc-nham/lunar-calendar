<?php namespace VanTran\LunarCalendar\Tests\Sunlongitude;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Mjd\DateTimeToMjd;
use VanTran\LunarCalendar\Sunlongitude\MjdToSunlongitude;

class MjdToSunglongitudeTest extends TestCase
{
    /**
     * Kiểm tra khởi tạo
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testInit(): void
    {
        $mjd = new DateTimeToMjd();
        $sl = new MjdToSunlongitude($mjd);

        $this->assertEquals($mjd->getJd(), $sl->getJd());
        $this->assertEquals($mjd->getMidnightJd(), $sl->getMidnightJd());
        $this->assertEquals($mjd->getOffset(), $sl->getOffset());
        $this->assertNotNull($sl->getDegrees()); // Không xác định đầu ra đúng hay sai
        
    }
}