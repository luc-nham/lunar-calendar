<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Converters\LunarGuaranteedToSexagenary;
use LucNham\LunarCalendar\LunarDateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarDateTimeToJd;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\LunarStringToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\LunarUnsafeToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Formatters\LunarDateTimeDefaultFormatter;
use LucNham\LunarCalendar\Terms\BranchIdentifier;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\SexagenaryIdentifier;
use LucNham\LunarCalendar\Terms\SexagenaryMilestone;
use LucNham\LunarCalendar\Terms\StemIdentifier;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\TestCase;

#[CoversClass(LunarGuaranteedToSexagenary::class)]
#[CoversClass(SexagenaryMilestone::class)]
#[CoversClass(SexagenaryTermAttribute::class)]
#[CoversClass(LunarDateTimeToJd::class)]
#[CoversClass(LunarStringToLunarGuaranteed::class)]
#[CoversClass(LunarUnsafeToLunarGuaranteed::class)]
#[CoversClass(LunarDateTimeDefaultFormatter::class)]
#[CoversClass(LunarDateTime::class)]
#[CoversClass(BranchIdentifier::class)]
#[CoversClass(StemIdentifier::class)]
#[CoversClass(SexagenaryIdentifier::class)]
#[CoversClass(JdToLunarDateTime::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[CoversClass(NewMoonToLunarFirstNewMoon::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(LunarFirstNewMoonPhase::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToLs::class)]
#[CoversClass(NewMoonIterator::class)]
#[CoversClass(LunarLeapMonthNewMoonPhase::class)]
#[CoversClass(LunarDateTimeGuaranteed::class)]
class LunarGuaranteedToSexagenaryTest extends TestCase
{
    public function testDayBeginToEnd()
    {
        $lunar = new LunarDateTime('2024-01-01 00:00 +0700');
        $se = new LunarGuaranteedToSexagenary(
            $lunar->getGuaranteedLunarDateTime(),
            $lunar->getOffset()
        );

        $terms = $se->getOutput();
        $utcTerms = $se->setOffset(0)->getOutput();

        $this->assertEquals('quy', $utcTerms->D->key);
        $this->assertEquals('mao', $utcTerms->d->key);

        $this->assertEquals('giap', $terms->D->key);
        $this->assertEquals('thin', $terms->d->key);

        $this->assertEquals('binh', $terms->M->key);
        $this->assertEquals('dan', $terms->m->key);

        $this->assertEquals('giap', $terms->Y->key);
        $this->assertEquals('thin', $terms->y->key);

        $this->assertEquals('giap', $terms->W->key);
        $this->assertEquals('thin', $terms->w->key);

        $this->assertEquals('giap', $terms->H->key);
        $this->assertEquals('ty', $terms->h->key);

        // End of day should be new sexagenary terms
        $lunar = new LunarDateTime('2024-01-01 23:00 +0700');
        $se = new LunarGuaranteedToSexagenary(
            $lunar->getGuaranteedLunarDateTime(),
            $lunar->getOffset()
        );
        $terms = $se->getOutput();

        $this->assertEquals('at', $terms->D->key);      // Changed
        $this->assertEquals('ti', $terms->d->key);      // Changed

        $this->assertEquals('binh', $terms->M->key);
        $this->assertEquals('dan', $terms->m->key);

        $this->assertEquals('giap', $terms->Y->key);
        $this->assertEquals('thin', $terms->y->key);

        $this->assertEquals('giap', $terms->W->key);
        $this->assertEquals('thin', $terms->w->key);

        $this->assertEquals('binh', $terms->H->key);    // Changed
        $this->assertEquals('ty', $terms->h->key);
    }

    public function testNewHourStemCycle()
    {
        $lunar = new LunarDateTime('2024-01-01 19:00 +0700');
        $se = new LunarGuaranteedToSexagenary(
            $lunar->getGuaranteedLunarDateTime(),
            $lunar->getOffset()
        );
        $terms = $se->getOutput();

        $this->assertEquals('giap', $terms->N->key); // Giap Ty is new hour 
        $this->assertEquals('giap', $terms->H->key); // Giap Tuat is new hour cycle
        $this->assertEquals('tuat', $terms->h->key);
    }
}
