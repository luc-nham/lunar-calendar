<?php

namespace LucNham\LunarCalendar\Tests;

use DateTime;
use LucNham\LunarCalendar\Attributes\SolarTermAttribute;
use LucNham\LunarCalendar\Contracts\SolarTermInterface;
use LucNham\LunarCalendar\Contracts\SolarTermNavigable;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToUnix;
use LucNham\LunarCalendar\Converters\UnixToJd;
use LucNham\LunarCalendar\Resolvers\SolarTermResolver;
use LucNham\LunarCalendar\SolarTerm;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;
use LucNham\LunarCalendar\Terms\VnSolarTermIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use TypeError;

#[CoversClass(SolarTerm::class)]
#[UsesClass(SolarTermAttribute::class)]
#[UsesClass(SolarTermIdentifier::class)]
#[UsesClass(JdToLs::class)]
#[UsesClass(JdToUnix::class)]
#[UsesClass(UnixToJd::class)]
#[UsesClass(SolarTermResolver::class)]
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

    public function testLocalTerm()
    {
        $date = new DateTime('2024-12-30');
        $default = SolarTerm::fromDate($date);
        $local = SolarTerm::fromDate(
            date: $date,
            target: VnSolarTermIdentifier::class
        );

        $this->assertEquals('Winter Solstice', $default->name);
        $this->assertEquals('winter_solstice', $default->key);

        $this->assertEquals('Đông Chí', $local->name);
        $this->assertEquals('dong_chi', $local->key);
    }

    public function testGetTimestampAndAngle()
    {
        $solarTerm = SolarTerm::now();

        $this->assertIsInt($solarTerm->getTimestamp());
        $this->assertIsFloat($solarTerm->getAngle());
    }

    public function testPhpDocBlock()
    {
        /**
         * @var SolarTermInterface&SolarTermNavigable
         */
        $st = new SolarTerm();
        $st->next()->next();

        $this->assertNotEmpty($st->key);
    }
}
