<?php namespace VanTran\LunarCalendar\Tests\Converters;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\BaseJDN;
use VanTran\LunarCalendar\Converters\JdnToDateTimeConverter;
use VanTran\LunarCalendar\Converters\JdnToNewMoonPhaseConverter;

class JdnToNewMoonPhaseConverterTest extends TestCase
{
    public function testOuput(): void
    {
        // [
        //     'jd' => 2459936.9291239106,
        //     'cycles' => 1521,
        //     'date' => '2022-12-23 17:17:56 +07:00'
        // ];

        $jdn = new BaseJDN(2459936.9291239106 + 1);
        $nmC = new JdnToNewMoonPhaseConverter($jdn);
        $ouputDate = (new JdnToDateTimeConverter($nmC))->getDateTime();

        $this->assertEquals($jdn->getJd() - 1, $nmC->getJd());
        $this->assertEquals('2022-12-23 10:17 +00:00', $ouputDate->format('Y-m-d H:i P'));
    }
}