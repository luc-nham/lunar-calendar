<?php

namespace LucNham\LunarCalendar\Tests\Issues;

use LucNham\LunarCalendar\LunarDateTime;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * @link https://github.com/luc-nham/lunar-calendar/issues/82
 */
#[CoversNothing]
class Issues82Test extends TestCase
{
    public function testFixed()
    {
        $lunar = new LunarDateTime('2020-04-01 +0700 (+)');
        $this->assertEquals('04+', $lunar->format('L'));

        $lunar = new LunarDateTime('2020-04-01 +0700');
        $this->assertEquals('04', $lunar->format('L'));

        $lunar = new LunarDateTime('2033-11-01 +0700 (+)');
        $this->assertEquals('11+', $lunar->format('L'));
    }
}
