<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Converters\Traits\JdInputSetable;
use LucNham\LunarCalendar\Terms\TimeInterval;

/**
 * Converts Julian day number input to hours, minutes, seconds
 */
class JdToTime extends Converter
{
    use JdInputSetable;

    /**
     * Create new converter
     *
     * @param float $jd         Julian day number input
     * @param integer $offset   Timezone offset in seconds, default 0 mean UTC
     */
    public function __construct(private float $jd = 0.5, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Get the hours, minutes, seconds from Julian day number input
     *
     * @return TimeInterval
     */
    public function getOutput(): TimeInterval
    {
        $totalSec = round(
            ($this->jd - floor($this->jd))
                * 86400
                + $this->offset()
                + 43200,
            1
        );

        $totalSec = floor($totalSec);

        return new TimeInterval(
            h: floor($totalSec / 3600) % 24,
            i: floor($totalSec / 60) % 60,
            s: floor($totalSec % 60)
        );
    }
}
