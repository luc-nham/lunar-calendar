<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use DateTime;
use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToUnix;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToUnix::class)]
class JdToUnixTest extends TestCase
{
    public function testDefault()
    {
        $c = new JdToUnix();
        $this->assertEquals(0, $c->getOutput());
    }

    #[CoversNothing]
    public function testLager()
    {
        $date = new DateTime('1970-01-01 00:00 +00:00');

        for ($i = 0; $i < 86400; $i++) {
            $interval = new DateTimeInterval(
                d: $date->format('j'),
                m: $date->format('n'),
                y: $date->format('Y'),
                h: $date->format('G'),
                i: (int)$date->format('i'),
                s: (int)$date->format('s')
            );

            $jd = (new GregorianToJd($interval))->getOutput();
            $unix = (new JdToUnix($jd))->getOutput();

            $this->assertEquals($date->getTimestamp(), $unix);

            $date->setTimestamp($date->getTimestamp() + 1);
        }
    }
}
