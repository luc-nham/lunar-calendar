<?php

namespace LucNham\LunarCalendar\Tests;

use DateTime;
use LucNham\LunarCalendar\Attributes\SolarTermAttribute;
use LucNham\LunarCalendar\Converters\DateTimeIntervalToDateTimeString;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\JdToUnix;
use LucNham\LunarCalendar\Converters\UnixToJd;
use LucNham\LunarCalendar\SolarTerm;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;
use LucNham\LunarCalendar\Terms\SolarTermMilestone;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypeError;

#[CoversClass(SolarTerm::class)]
#[CoversClass(SolarTerm::class)]
#[CoversClass(SolarTermAttribute::class)]
#[CoversClass(SolarTermIdentifier::class)]
#[CoversClass(JdToLs::class)]
#[CoversClass(DateTimeIntervalToDateTimeString::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(SolarTermMilestone::class)]
#[CoversClass(JdToUnix::class)]
#[CoversClass(UnixToJd::class)]
class SolarTermTest extends TestCase
{
    public function testMagicGetter()
    {
        $st = new SolarTerm();
        $begin = $st->begin;

        $this->assertTrue(is_string($st->name));
        $this->assertTrue(is_string($st->key));
        $this->assertTrue(is_string($st->type));
        $this->assertTrue(is_integer($st->position));
        $this->assertTrue(is_float($st->ls));

        $this->assertInstanceOf(SolarTermMilestone::class, $begin);

        $this->expectExceptionMessage("Attribute 'bad_prop' does not exist");
        $st->bad_prop;
    }

    public function testRosolveTermBadTarget()
    {
        $this->expectException(TypeError::class);
        new SolarTerm(TestCase::class);
    }

    public function testRosolveTermBadPostion()
    {
        $this->expectExceptionMessage("The Solar term corresponding to position 50 could not be found");
        $term = (new class() extends SolarTerm {
            public function getTerm(): SolarTermIdentifier
            {
                return $this->resolveTerm(50);
            }
        })->getTerm();
    }

    public function testCurrentMilestone()
    {
        $date = new DateTime();
        $st = SolarTerm::fromDate($date);
        $current = $st->current;

        $this->assertEquals($date->getTimestamp(), $current->unix);
    }

    public function testCurrentSameBegin()
    {
        $point = (new SolarTerm(0))->getBeginMilestone();

        $st = new SolarTerm($point->unix);
        $begin = $st->begin;
        $current = $st->current;

        $this->assertEquals($point->unix, $begin->unix);
        $this->assertEquals($current->angle, $begin->angle);
        $this->assertEquals($current->jd, $begin->jd);
    }

    public function testNextTerm()
    {
        $start = new SolarTerm(0);

        for ($i = 1; $i <= 7; $i++) {
            $next = $start->next();
            $diff = $next->begin->jd - $start->begin->jd;

            $this->assertTrue($diff >= 14 && $diff <= 17);

            $start = $next;
        }
    }

    public function testPrevTerm()
    {
        $start = new SolarTerm();

        for ($i = 1; $i <= 24; $i++) {
            $prev = $start->previous();
            $diff = $start->begin->jd - $prev->begin->jd;

            $this->assertTrue($diff >= 14 && $diff <= 17);
            $start = $prev;
        }
    }
}
