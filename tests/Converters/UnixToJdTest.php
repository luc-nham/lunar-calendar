<?php

namespace LucNham\LunarCalendar\Converters;

use DateInterval;
use DateTime;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversClass(UnixToJd::class)]
class UnixToJdTest extends TestCase
{
    public function testDefault()
    {
        $jd = (new UnixToJd())->getOutput();
        $this->assertEquals(2440587.5, $jd);
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
            $jd2 = (new UnixToJd($date->getTimestamp()))->getOutput();

            $this->assertEquals($jd, $jd2);

            $date->add(new DateInterval('PT1S'));
        }
    }
}
