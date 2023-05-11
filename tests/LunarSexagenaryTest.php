<?php namespace VanTran\LunarCalendar\Tests;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\LunarSexagenary;

class LunarSexagenaryTest extends TestCase
{
    /**
     * @var LunarSexagenary
     */
    private $sexagenary;

    public function setup(): void
    {
        $lunar = LunarDateTime::createFromGregorian('2023-04-30 13:00 +07:00');
        $this->sexagenary = new LunarSexagenary($lunar);
    }

    public function testFormat(): void
    {
        $expected = 'Ngày Mậu Ngọ, tháng Bính Thìn, năm Quý Mão, giờ Kỷ Mùi';
        $format = 'Ngày D+, tháng M+, năm Y+, giờ H+';

        $this->assertEquals($expected, $this->sexagenary->format($format));
    }

    public function testGetTerms(): void
    {
        $dayStem = $this->sexagenary->getTerm('D');
        $this->assertEquals(4, $dayStem->getIndex());
        $this->assertEquals('e', $dayStem->getCharacter());
        $this->assertEquals('stem', $dayStem->getType());

        $dayBranch = $this->sexagenary->getTerm('d');
        $this->assertEquals(6, $dayBranch->getIndex());
        $this->assertEquals('g', $dayBranch->getCharacter());
        $this->assertEquals('branch', $dayBranch->getType());
    }
}