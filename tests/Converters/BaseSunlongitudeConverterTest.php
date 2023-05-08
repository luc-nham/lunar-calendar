<?php namespace VanTran\LunarCalendar\Tests\Converters;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\BaseSunlongitudeConverter;

class BaseSunlongitudeConverterTest extends TestCase
{
    public function testOuput(): void
    {
        $epochDegrees = 280.159893394254;
        $slC = new BaseSunlongitudeConverter(BaseSunlongitudeConverter::EPOCH_JD);
        $diff = abs($epochDegrees - $slC->getDegrees(true));

        $this->assertLessThanOrEqual(0.01, $diff);
    }
}