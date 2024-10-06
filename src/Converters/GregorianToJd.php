<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\DateTimeInterval;

/**
 * Converter that converts a Gregorian date time to Julian day number
 */
class GregorianToJd extends Converter
{
    /**
     * Create new converter
     *
     * @param DateTimeInterval $g Gregorian date time interval, default corresponding to 1970-01-01
     * @param integer $offset     Timezone offset, default 0 mean UTC+0
     */
    public function __construct(private DateTimeInterval $g = new DateTimeInterval(), int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Set new Gregorian input to get diffirent ouput without creating new converter
     *
     * @param DateTimeInterval $g
     * @return self
     */
    public function setInput(DateTimeInterval $g): self
    {
        $this->g = $g;
        return $this;
    }

    /**
     * Get ouput Julian day number
     *
     * @return float
     */
    public function getOutput(): float
    {
        $a = floor((14 - $this->g->m) / 12);
        $y = $this->g->y + 4800 - $a;
        $m = $this->g->m + 12 * $a - 3;
        $j = $this->g->d + floor((153 * $m + 2) / 5)
            + 365 * $y
            + floor($y / 4)
            - floor($y / 100)
            + floor($y / 400)
            - 32045;


        $j += (($this->g->h - 12) % 24 * 3600 + $this->g->i * 60 + $this->g->s) / 86400;

        if ($this->offset() !== 0) {
            $j -= $this->offset() / 86400;
        }

        return $this->toFixed($j);
    }
}
