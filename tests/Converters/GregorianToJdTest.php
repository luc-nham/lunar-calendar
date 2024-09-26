<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

#[CoversClass(GregorianToJd::class)]
#[CoversClass(DateTimeInterval::class)]
class GregorianToJdTest extends TestCase
{
    public function testDefaultConfig()
    {
        // Default 1970-01-01T00:00+0000
        $c = new GregorianToJd();

        $c->forward(function ($o) {
            $this->assertEquals(2440587.5, $o);
        });

        return $c;
    }

    #[Depends('testDefaultConfig')]
    public function testInputChanges(GregorianToJd $c)
    {
        $c->setInput(new DateTimeInterval(2, 1, 1970))->forward(fn($o) =>  $this->assertEquals(2440588.5, $o));

        return $c;
    }

    #[Depends('testInputChanges')]
    public function testOffsetChanges(GregorianToJd $c)
    {
        $c->setOffset(25200)->forward(fn($o) => $this->assertEquals(2440588.2083333, $o));
        $c->setOffset(-25200)->forward(fn($o) => $this->assertEquals(2440588.7916667, $o));

        return $c;
    }

    #[Depends('testOffsetChanges')]
    public function testFixedChanges(GregorianToJd $c)
    {
        $c->setFixed(6)->forward(fn($o) => $this->assertEquals(2440588.791667, $o));

        return $c;
    }
}
