<?php

namespace LucNham\LunarCalenda\Tests\Formatters;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarDateTimeToJd;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\LunarGuaranteedToSexagenary;
use LucNham\LunarCalendar\Converters\LunarStringToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\LunarUnsafeToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Formatters\LunarDateTimeDefaultFormatter;
use LucNham\LunarCalendar\Formatters\SexagenaryDefaultFormatter;
use LucNham\LunarCalendar\LunarDateTime;
use LucNham\LunarCalendar\Resolvers\BranchTermResolver;
use LucNham\LunarCalendar\Resolvers\StemTermResolver;
use LucNham\LunarCalendar\Terms\BranchIdentifier;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\SexagenaryIdentifier;
use LucNham\LunarCalendar\Terms\SexagenaryMilestone;
use LucNham\LunarCalendar\Terms\StemIdentifier;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SexagenaryDefaultFormatter::class)]
#[UsesClass(LunarDateTime::class)]
#[UsesClass(SexagenaryTermAttribute::class)]
#[UsesClass(GregorianToJd::class)]
#[UsesClass(JdToLunarDateTime::class)]
#[UsesClass(JdToGregorian::class)]
#[UsesClass(JdToLunarNewMoon::class)]
#[UsesClass(JdToMidnightJd::class)]
#[UsesClass(JdToTime::class)]
#[UsesClass(LunarDateTimeToJd::class)]
#[UsesClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[UsesClass(LunarGuaranteedToSexagenary::class)]
#[UsesClass(LunarStringToLunarGuaranteed::class)]
#[UsesClass(LunarUnsafeToLunarGuaranteed::class)]
#[UsesClass(NewMoonIterator::class)]
#[UsesClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[UsesClass(NewMoonToLunarFirstNewMoon::class)]
#[UsesClass(LunarDateTimeDefaultFormatter::class)]
#[UsesClass(BranchIdentifier::class)]
#[UsesClass(StemIdentifier::class)]
#[UsesClass(DateTimeInterval::class)]
#[UsesClass(TimeInterval::class)]
#[UsesClass(SexagenaryMilestone::class)]
#[UsesClass(SexagenaryIdentifier::class)]
#[UsesClass(LunarDateTimeGuaranteed::class)]
#[UsesClass(LunarDateTimeInterval::class)]
#[UsesClass(LunarFirstNewMoonPhase::class)]
#[UsesClass(NewMoonPhase::class)]
#[UsesClass(BranchTermResolver::class)]
#[UsesClass(StemTermResolver::class)]
class SexagenaryDefaultFormatterTest extends TestCase
{
    public function testSingleTerm()
    {
        $formatter = new SexagenaryDefaultFormatter();
        $lunar = new LunarDateTime('2024-09-22 10:20 +07:00');
        $terms = (new LunarGuaranteedToSexagenary(
            $lunar->getGuaranteedLunarDateTime(),
            $lunar->getOffset()
        ))->getOutput();

        $this->assertEquals('Tan', $formatter->format('D', $terms));
        $this->assertEquals('Tan', $formatter->format('[D]', $terms));

        $this->assertEquals('Dau', $formatter->format('d', $terms));
        $this->assertEquals('Dau', $formatter->format('[d]', $terms));

        $this->assertEquals('Giap', $formatter->format('M', $terms));
        $this->assertEquals('Giap', $formatter->format('[M]', $terms));

        $this->assertEquals('Tuat', $formatter->format('m', $terms));
        $this->assertEquals('Tuat', $formatter->format('[m]', $terms));

        $this->assertEquals('Giap', $formatter->format('Y', $terms));
        $this->assertEquals('Giap', $formatter->format('[Y]', $terms));

        $this->assertEquals('Thin', $formatter->format('y', $terms));
        $this->assertEquals('Thin', $formatter->format('[y]', $terms));

        $this->assertEquals('Quy', $formatter->format('H', $terms));
        $this->assertEquals('Quy', $formatter->format('[H]', $terms));

        $this->assertEquals('Ti', $formatter->format('h', $terms));
        $this->assertEquals('Ti', $formatter->format('[h]', $terms));

        $this->assertEquals('Giap', $formatter->format('W', $terms));
        $this->assertEquals('Giap', $formatter->format('[W]', $terms));

        $this->assertEquals('Dan', $formatter->format('w', $terms));
        $this->assertEquals('Dan', $formatter->format('[w]', $terms));

        $this->assertEquals('Mau', $formatter->format('N', $terms));
        $this->assertEquals('Mau', $formatter->format('[N]', $terms));
    }

    public function testMultipleTerm()
    {
        $formatter = new SexagenaryDefaultFormatter();
        $lunar = new LunarDateTime('2024-09-22 10:20 +07:00');
        $terms = (new LunarGuaranteedToSexagenary(
            $lunar->getGuaranteedLunarDateTime(),
            $lunar->getOffset()
        ))->getOutput();


        $formatted = $formatter->format(
            'Day of [D d], month of [M m], year of [Y y], hour of [H h]',
            $terms
        );
        $formatted2 = $formatter->format(
            'Day of [D+], month of [M+], year of [Y+], hour of [H+]',
            $terms
        );

        $expected = 'Day of Tan Dau, month of Giap Tuat, year of Giap Thin, hour of Quy Ti';

        $this->assertEquals($expected, $formatted);
        $this->assertEquals($expected, $formatted2);
    }

    public function testBadFormatters()
    {
        $formatter = new SexagenaryDefaultFormatter();
        $lunar = new LunarDateTime('2024-09-22 10:20 +07:00');
        $terms = (new LunarGuaranteedToSexagenary(
            $lunar->getGuaranteedLunarDateTime(),
            $lunar->getOffset()
        ))->getOutput();

        $this->assertEquals('K', $formatter->format('K', $terms));
        $this->assertEquals('Day of D d', $formatter->format('Day of D d', $terms));
    }
}
