<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Converters\Traits\JdInputSetable;

/**
 * Converts Julian day number to  Unix timestamp
 */
class JdToUnix extends Converter
{
    use JdInputSetable;

    /**
     * Create new converter
     *
     * @param float $jd Julian day number, default 2440587.5 corresponds to 1970-01-01 00:00 +00:00
     */
    public function __construct(private float $jd = 2440587.5) {}

    /**
     * Returns Unix timestamp corresponding to Julian day number input
     *
     * @return int
     */
    public function getOutput(): int
    {
        $u = round($this->jd - 2440587.5, 6) * 86400;
        $fu = floor($u);

        return $u - $fu >= 0.5 ? ceil($u) : $fu;
    }
}
