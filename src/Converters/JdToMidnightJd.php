<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Converters\Traits\JdInputSetable;

/**
 * Converter that converts a Julian day number at any time in day to Julian day number at midnight
 */
class JdToMidnightJd extends Converter
{
    use JdInputSetable;

    /**
     * Create new converter
     *
     * @param integer|float $jd Julian day number input, default corresponding to 1970-01-01T00:00+0000
     * @param integer $offset   Timezone offset in seconds, default 0 mean UTC+0 
     */
    public function __construct(private int|float $jd = 2440587.5, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Return Julian day number at midnight (00:00)
     *
     * @return float
     */
    public function getOuput(): float
    {
        $jd = $this->jd;
        $diff = $this->toFixed(($jd - floor($jd)));
        $utcMidnight =
            $diff >= 0.5 ? floor($jd) + 0.5 : floor($jd) - 0.5;

        if ($this->offset() === 0) {
            return $utcMidnight;
        }

        $diff2 = $this->toFixed($this->offset() / 86400);

        if ($diff === 0.5 - $diff2) {
            return $jd;
        }

        $decimal = 1 - $diff2;

        $midnight =
            $jd >= $utcMidnight + $decimal
            ? $utcMidnight + $decimal
            : $utcMidnight + $decimal - 1;

        return $this->toFixed($midnight);
    }
}
