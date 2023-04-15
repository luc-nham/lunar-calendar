<?php namespace VanTran\LunarCalendar\Tests\Sunlongitude;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Sunlongitude\BaseSunlongitude;

class BaseSunlongitudeTest extends TestCase
{
    /**
     * Kiểm tra truy xuất giá trị
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testRecivingValue(): void
    {
        $sl = new BaseSunlongitude(12345, 0.1, 0);

        $this->assertEquals(12345, $sl->getJd());
        $this->assertEquals(0.1, $sl->getDegrees(true));
        $this->assertEquals(0, $sl->getOffset());
    }
}