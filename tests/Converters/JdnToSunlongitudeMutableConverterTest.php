<?php namespace VanTran\LunarCalendar\Tests\Converters;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\DateTimeToJdnConverter;
use VanTran\LunarCalendar\Converters\JdnToSunlongitudeMutableConverter;

class JdnToSunlongitudeMutableConverterTest extends TestCase
{
    /**
     * @var DateTimeToJdnConverter
     */
    private $jd;

    /**
     * @var DateTime
     */
    private $date;

    public function setup(): void
    {
        $this->date = new DateTime('2023-05-15', new DateTimeZone('UTC'));
        $this->jd = new DateTimeToJdnConverter($this->date);
    }

    /**
     * Kiểm tra trả về vị trí mới từ góc cộng thêm
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testDegreesAdition(): void
    {
        $slC = new JdnToSunlongitudeMutableConverter($this->jd);
        $inputDeg = 53.933;
        $this->assertEquals($inputDeg, $slC->getDegrees(true));

        $disiredDeg = 120;
        $add = $slC->add($disiredDeg);
        $diffDays = $add->getJd() - $slC->getJd();

        $this->assertTrue($inputDeg + $disiredDeg - $add->getDegrees(true) <= 0.001);
        $this->assertTrue($diffDays > $disiredDeg);
    }

    public function testDegreesSubtraction(): void
    {
        $slC = new JdnToSunlongitudeMutableConverter($this->jd);
        $inputDeg = $slC->getDegrees(true);

        $disiredDeg = 361;
        $subtracted = $slC->subtract($disiredDeg);
        $expectedDeg = $inputDeg - $disiredDeg;

        while($expectedDeg < 0) {
            $expectedDeg += 360;
        }

        $expectedDeg = round($expectedDeg, 3);
        
        $this->assertEquals($expectedDeg, $subtracted->getDegrees(true));
        $this->assertLessThan($slC->getJd(), $subtracted->getJd());
    }
}