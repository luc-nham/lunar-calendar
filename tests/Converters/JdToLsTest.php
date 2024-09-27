<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\JdToLs;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToLs::class)]
class JdToLsTest extends TestCase
{
    public function testDefault()
    {
        $c = new JdToLs();
        $c->setFixed(2);

        $c->forward(fn($o) => $this->assertEquals(280.15, $o));
    }
}
