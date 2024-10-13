<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;

/**
 * Converts a Lunar date time interval to Julian day number
 */
class LunarDateTimeToJd extends Converter
{
    /**
     * Create new converter
     *
     * @param LunarDateTimeInterval $lunar  Lunar date time interval
     * @param integer $offset               Timezone offset in seconds, default 0 mean UTC
     */
    public function __construct(private LunarDateTimeInterval $lunar = new LunarDateTimeInterval(), int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Return the Julian day number corresponding to Lunar date time interval input
     *
     * @return float
     */
    public function getOutput(): float
    {
        /** @var LunarFirstNewMoonPhase */
        $firstNewMoon = (new GregorianToJd(
            g: new DateTimeInterval(
                d: 31,
                m: 12,
                y: $this->lunar->y
            ),
            offset: $this->offset()
        ))
            ->then(JdToLunarNewMoon::class)
            ->then(NewMoonToLunarFirstNewMoon::class)
            ->getOutput();

        $leapNewMoon = (new LunarFirstNewMoonToLunarLeapNewMoon(
            nm: $firstNewMoon,
            offset: $this->offset()
        ))->getOutput();

        $trueLeap = $this->lunar->leap;

        if ($this->lunar->leap) {
            if (!$leapNewMoon) {
                $trueLeap = false;
            } else {
                if ($this->lunar->m !== $leapNewMoon->month) {
                    $trueLeap = false;
                }
            }
        }

        $key = $this->lunar->m - 1;

        if ($leapNewMoon && ($trueLeap || $this->lunar->m > $leapNewMoon->month)) {
            $key += 1;
        }

        $newMonIterator = new NewMoonIterator(nm: $firstNewMoon, offset: $this->offset());
        $newMonIterator->setKey($key);

        $newMoon = $newMonIterator->current();
        $jd = $newMoon->jd + $this->lunar->d - 1;
        $frag = ($this->lunar->h * 3600 + $this->lunar->i * 60 + $this->lunar->s) / 86400;

        return $this->toFixed($jd + $frag);
    }
}
