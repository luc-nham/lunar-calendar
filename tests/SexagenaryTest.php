<?php

namespace LucNham\LunarCalenda\Tests;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
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
use LucNham\LunarCalendar\Sexagenary;
use LucNham\LunarCalendar\Terms\BranchIdentifier;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\SexagenaryIdentifier;
use LucNham\LunarCalendar\Terms\SexagenaryMilestone;
use LucNham\LunarCalendar\Terms\StemIdentifier;
use LucNham\LunarCalendar\Terms\TimeInterval;
use LucNham\LunarCalendar\Terms\VnBranchIdentifier;
use LucNham\LunarCalendar\Terms\VnStemIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Sexagenary::class)]
#[CoversClass(VnStemIdentifier::class)]
#[CoversClass(VnBranchIdentifier::class)]
#[UsesClass(SexagenaryDefaultFormatter::class)]
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
#[UsesClass(JdToLs::class)]
#[UsesClass(LunarLeapMonthNewMoonPhase::class)]
#[UsesClass(BranchTermResolver::class)]
#[UsesClass(StemTermResolver::class)]
class SexagenaryTest extends TestCase
{
    public function testMagicGet(): Sexagenary
    {
        $se = new Sexagenary(new LunarDateTime('2025-07-30 22:00 +07:00'));

        $this->assertEquals('quy', $se->D->key);
        $this->assertEquals('ti', $se->d->key);

        $this->assertEquals('giap', $se->M->key);
        $this->assertEquals('than', $se->m->key);

        $this->assertEquals('at', $se->Y->key);
        $this->assertEquals('ti', $se->y->key);

        $this->assertEquals('nham', $se->N->key);
        $this->assertEquals('quy', $se->H->key);
        $this->assertEquals('hoi', $se->h->key);

        $this->assertEquals('giap', $se->W->key);
        $this->assertEquals('than', $se->w->key);

        return $se;
    }

    #[Depends('testMagicGet')]
    public function testMagicGetException(Sexagenary $se)
    {
        $this->expectExceptionMessage("Target property 'bad_prop' dose not exists");
        $se->bad_prop;
    }

    #[Depends('testMagicGet')]
    public function testFormat(Sexagenary $se)
    {
        $expected = 'D: Quy Ti, M: Giap Than, Y: At Ti, H: Quy Hoi, W: Giap Than';
        $formatter = 'D: [D+], M: [M+], Y: [Y+], H: [H+], W: [W+]';

        $this->assertEquals($expected, $se->format($formatter));
    }

    public function testLocalization()
    {
        $se = new Sexagenary(
            lunar: new LunarDateTime('2025-07-30 22:00 +07:00'),
            stemIdetifier: VnStemIdentifier::class,
            branchIdentifier: VnBranchIdentifier::class
        );

        $expected = 'Ngày Quý Tị, tháng Giáp Thân, năm Ất Tị, giờ Quý Hợi';
        $formatter = 'Ngày [D+], tháng [M+], năm [Y+], giờ [H+]';

        $this->assertEquals($expected, $se->format($formatter));
    }
}
