<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

#[CoversClass(GregorianToJd::class)]
#[CoversClass(NewMoonIterator::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
class NewMoonIteratorTest extends TestCase
{
    public function testNavigationToNext()
    {
        /** @var NewMoonPhase */
        $newMoon = (new GregorianToJd())
            ->forward(fn(float $jd) => (new JdToLunarNewMoon($jd))->getOutput());

        $iterator = new NewMoonIterator($newMoon);

        for ($i = 1; $i <= 10; ++$i) {
            $iterator->next();
            $currentNm = $iterator->current();

            $this->assertEquals($newMoon->total + $i, $currentNm->total);
            $this->assertTrue($currentNm->jd >= $newMoon->jd + (29 * $i));
        }

        return $newMoon;
    }

    #[Depends("testNavigationToNext")]
    public function testNavigationToPrevious(NewMoonPhase $newMoon)
    {
        $iterator = new NewMoonIterator($newMoon, 0, true);

        for ($i = 1; $i <= 10; ++$i) {
            $iterator->next();
            $currentNm = $iterator->current();

            $this->assertEquals($newMoon->total - $i, $currentNm->total);
            $this->assertTrue($currentNm->jd <= $newMoon->jd - (29 * $i));
        }

        return $iterator;
    }

    #[Depends("testNavigationToNext")]
    #[Depends("testNavigationToPrevious")]
    public function testReWind(NewMoonPhase $newMoon, NewMoonIterator $iterator)
    {
        $iterator->rewind();

        $this->assertTrue($newMoon === $iterator->current());
    }

    #[Depends("testNavigationToPrevious")]
    public function testValid(NewMoonIterator $iterator)
    {
        $this->assertTrue($iterator->valid());
    }

    #[Depends("testNavigationToNext")]
    public function testSetKey(NewMoonPhase $newMoon)
    {
        $iterator = new NewMoonIterator($newMoon);
        $iterator->setKey(12);

        $this->assertTrue($newMoon->total + 12 === $iterator->current()->total);
    }
}
