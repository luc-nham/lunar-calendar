<?php namespace VanTran\LunarCalendar\Tests\Converters;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\DateTimeToJdnConverter;
use VanTran\LunarCalendar\Converters\JdnToSolarTermConverter;
use VanTran\LunarCalendar\Interfaces\SolarTermInterface;

class JdnToSolarTermConverterTest extends TestCase
{
    private $jd;
    private $timezone;

    /**
     * {@inheritdoc}
     */
    public function setup(): void
    {
        $this->timezone = new DateTimeZone('+0700');
        $this->jd = new DateTimeToJdnConverter(
            new DateTime('2023-05-13', $this->timezone)
        );
    }

    public function testCurrentTerm(): SolarTermInterface
    {
        // Ngày 13 tháng 05 năm 2023 nằm trong tiết Lập Hạ, Kinh độ Mặt trời từ 45 - 59 độ
        $stC = new JdnToSolarTermConverter($this->jd);

        $this->assertEquals(3, $stC->getIndex());
        $this->assertEquals('d', $stC->getCharacter());
        $this->assertEquals('J', $stC->getType());
        $this->assertEquals('Lập Hạ', $stC->getLabel());

        $this->assertLessThan(60, $stC->getDegrees(true));
        $this->assertGreaterThanOrEqual(45, $stC->getDegrees(true));

        return $stC;
    }

    #[Depends('testCurrentTerm')]
    public function testBeginPosition(SolarTermInterface $current): void
    {
        $begin = $current->begin(); // Lập Hạ

        $this->assertEquals(45, $begin->getDegrees());
        $this->assertEquals($current->getIndex(), $begin->getIndex());
    }

    #[Depends('testCurrentTerm')]
    public function testNextPosition(SolarTermInterface $current): void
    {
        $next = $current->next(1);  // Tiểu Mãn
        $next2 = $current->next(2); // Mang Chủng

        $this->assertEquals(60, $next->getDegrees());
        $this->assertEquals('e', $next->getCharacter());
        $this->assertEquals('Z', $next->getType());
        $this->assertEquals($current->getIndex() + 1, $next->getIndex());
        $this->assertEquals('Tiểu Mãn', $next->getLabel());

        $this->assertEquals($next->getDegrees() + 15, $next2->getDegrees());
        $this->assertEquals('f', $next2->getCharacter());
        $this->assertEquals('J', $next2->getType());
        $this->assertEquals($next->getIndex() + 1, $next2->getIndex());
        $this->assertEquals('Mang Chủng', $next2->getLabel());
    }

    #[Depends('testCurrentTerm')]
    public function testPrevPosition(SolarTermInterface $current): void
    {
        $prev3 = $current->previuos(3); // Xuân Phân
        $prev4 = $current->previuos(4); // Kinh Trập

        $this->assertEquals(0, $prev3->getDegrees());
        $this->assertEquals('a', $prev3->getCharacter());
        $this->assertEquals('Z', $prev3->getType());
        $this->assertEquals(0, $prev3->getIndex());
        $this->assertEquals('Xuân Phân', $prev3->getLabel());

        $this->assertEquals(345, $prev4->getDegrees());
        $this->assertEquals('x', $prev4->getCharacter());
        $this->assertEquals('J', $prev4->getType());
        $this->assertEquals(23, $prev4->getIndex());
        $this->assertEquals('Kinh Trập', $prev4->getLabel());
    }
}