<?php

namespace LucNham\LunarCalenda\Tests;

use DateTime;
use Exception;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\UnixToJd;
use LucNham\LunarCalendar\Enums\SolarTermDurationMode;
use LucNham\LunarCalendar\Resolvers\SolarTermResolver;
use LucNham\LunarCalendar\SolarTerm;
use LucNham\LunarCalendar\SolarTermDuration;
use LucNham\LunarCalendar\Terms\SolarTermDurationStorage;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SolarTermDuration::class)]
#[CoversClass(SolarTermDurationStorage::class)]
#[UsesClass(SolarTerm::class)]
#[UsesClass(JdToLs::class)]
#[UsesClass(UnixToJd::class)]
#[UsesClass(SolarTermResolver::class)]
#[UsesClass(SolarTermIdentifier::class)]
class SolarTermDurationTest extends TestCase
{
    public function testNormalMode()
    {
        $date = new DateTime('2024-02-06 UTC');
        $term = SolarTerm::fromDate($date);
        $duration = new SolarTermDuration($term);

        $this->assertEquals(15, $duration->total);
        $this->assertEquals($duration->total, $duration->getTotal());

        $this->assertEquals(2, $duration->passed);
        $this->assertEquals($duration->passed, $duration->getPassed());

        $this->assertEquals(13, $duration->remain);
        $this->assertEquals($duration->remain, $duration->getRemain());

        $this->assertEquals(SolarTermDurationMode::NORMAL, $duration->mode);
        $this->assertEquals($duration->mode, $duration->getMode());

        $this->expectExceptionMessage("Property 'BAD_PROP' dose not exists");
        $duration->BAD_PROP;
    }

    public function testStrictMode()
    {
        $date = new DateTime('2024-02-06 UTC');
        $term = SolarTerm::fromDate($date);
        $duration = new SolarTermDuration($term, SolarTermDurationMode::STRICT);

        $this->assertEquals(SolarTermDurationMode::STRICT, $duration->mode);
        $this->assertEquals($duration->mode, $duration->getMode());
        $this->assertEquals($duration->getTotal(), $duration->getPassed() + $duration->getRemain());

        $arr = $duration->toArray();
        $this->assertEquals('strict', $arr['mode']);
    }

    public function testToArray()
    {
        $date = new DateTime('2024-02-06 UTC');
        $term = SolarTerm::fromDate($date);
        $duration = (new SolarTermDuration($term))->toArray();

        $this->assertTrue(isset($duration['mode']));
        $this->assertTrue(isset($duration['passed']));
        $this->assertTrue(isset($duration['remain']));
        $this->assertTrue(isset($duration['total']));

        $this->assertEquals('normal', $duration['mode']);
    }
}
