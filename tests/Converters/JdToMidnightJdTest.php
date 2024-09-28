<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToMidnightJd::class)]
class JdToMidnightJdTest extends TestCase
{
    public function testDefault()
    {
        // Default input 1970-01-01T00:00+0000
        $c = new JdToMidnightJd();

        $c->forward(fn($j) => $this->assertEquals(2440587.5, $j));

        return $c;
    }

    #[Depends('testDefault')]
    public function testChangeJdInput(JdToMidnightJd $c)
    {
        // Change input to 1970-01-01T12:00+0000
        $c->setJd(2440587.5 + 0.5)->forward(fn($j) => $this->assertEquals(2440587.5, $j));

        return $c;
    }

    #[Depends('testChangeJdInput')]
    public function testChangeOffset(JdToMidnightJd $c)
    {
        // Change offset to 1970-01-01T12:00+0700
        $c->setOffset(25200)->forward(fn($j) => $this->assertEquals(2440587.2083333, $j));

        // Change offset to 1970-01-01T12:00-0700
        $c->setOffset(-25200)->forward(fn($j) => $this->assertEquals(2440587.7916667, $j));
    }
}
