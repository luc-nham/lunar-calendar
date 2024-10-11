<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\DateTimeInterval;

/**
 * Converter that converts a Julian day number to Gregorian date time
 */
class JdToGregorian extends Converter
{
    /**
     * Create converter
     *
     * @param float $jd     Julian day number. Default value corresponding to 1970-01-01T00:00+0000
     * @param int $offset   Timezone offset to get local time output
     */
    public function __construct(private float $jd = 2440587.5, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Change input value
     *
     * @param float $jd
     * @return self
     */
    public function setInput(float $jd): self
    {
        $this->jd = $jd;
        return $this;
    }

    /**
     * Return a DateTimeInterval object type that stores Gregorian date time 
     */
    public function getOutput(): DateTimeInterval
    {
        $j1 = $this->toFixed($this->jd + $this->offset() / 86400);
        $j = floor($j1);

        if ($j1 - $j >= 0.5) {
            $j += 1;
        }

        $j = $j - 1721119;
        $y = floor((4 * $j - 1) / 146097);
        $j = 4 * $j - 1 - 146097 * $y;
        $d = floor($j / 4);
        $j = floor((4 * $d + 3) / 1461);
        $d = 4 * $d + 3 - 1461 * $j;
        $d = floor(($d + 4) / 4);
        $m = floor((5 * $d - 3) / 153);
        $d = 5 * $d - 3 - 153 * $m;
        $d = floor(($d + 5) / 5);
        $y = 100 * $y + $j;

        if ($m < 10) {
            $m += 3;
        } else {
            $m -= 9;
            $y += 1;
        }

        $time =  (new JdToTime($this->jd, $this->offset()))->getOutput();

        return new DateTimeInterval(
            d: $d,
            m: $m,
            y: $y,
            h: $time->h,
            i: $time->i,
            s: $time->s
        );
    }
}
