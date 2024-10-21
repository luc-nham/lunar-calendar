<?php

namespace LucNham\LunarCalendar\Tests;

use DateTime;
use LucNham\LunarCalendar\Attributes\SolarTermAttribute;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToUnix;
use LucNham\LunarCalendar\Converters\UnixToJd;
use LucNham\LunarCalendar\SolarTerm;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypeError;

#[CoversClass(SolarTerm::class)]
#[CoversClass(SolarTermAttribute::class)]
#[CoversClass(SolarTermIdentifier::class)]
#[CoversClass(JdToLs::class)]
#[CoversClass(JdToUnix::class)]
#[CoversClass(UnixToJd::class)]
class SolarTermTest extends TestCase
{
    public function testMagicGetter()
    {
        $st = new SolarTerm();

        $this->assertTrue(is_string($st->name));
        $this->assertTrue(is_string($st->key));
        $this->assertTrue(is_string($st->type));
        $this->assertTrue(is_integer($st->position));
        $this->assertTrue(is_float($st->ls));
        $this->assertTrue(is_float($st->angle));

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

    public function testBeginningPoint()
    {
        $st1 = new SolarTerm(0);
        $begin1 = $st1->getBeginTimestamp();

        $st2 = new SolarTerm($begin1);
        $begin2 = $st2->getBeginTimestamp();

        $this->assertEquals($begin1, $begin2);
    }

    public function testNextTerm()
    {
        $start = new SolarTerm(0);

        for ($i = 1; $i <= 7; $i++) {
            $next = $start->next();
            $diff = ($next->getBeginTimestamp() - $start->getBeginTimestamp()) / 86400;

            $this->assertTrue($diff >= 14 && $diff <= 17);

            $start = $next;
        }
    }

    public function testPrevTerm()
    {
        $start = new SolarTerm();

        for ($i = 1; $i <= 24; $i++) {
            $prev = $start->previous();
            $diff = ($start->getBeginTimestamp() - $prev->getBeginTimestamp()) / 86400;

            $this->assertTrue($diff >= 14 && $diff <= 17);
            $start = $prev;
        }
    }

    public function testStaticInstance()
    {
        $date = new DateTime();
        $st1 = SolarTerm::now();
        $st2 = SolarTerm::fromDate($date);

        $this->assertTrue($st1->getBeginTimestamp() === $st2->getBeginTimestamp());
    }
}
