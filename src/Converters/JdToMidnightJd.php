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
    public function getOutput(): float
    {
        $diff = $this->offset() >= 43200 ? 1.5 : 0.5;
        $decimal = $diff - $this->offset() / 3600 / 24;
        $midnight = $this->toFixed(floor($this->jd) + $decimal);

        if ($midnight > $this->jd) {
            $midnight -= 1;
        }

        return $midnight;
    }
}
