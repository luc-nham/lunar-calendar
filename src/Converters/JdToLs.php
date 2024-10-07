<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Converters\Traits\JdInputSetable;

/**
 * Converter that converts a Julian day number to Solar longitude degress
 */
class JdToLs extends Converter
{
    use JdInputSetable;

    /**
     * Create new converter
     *
     * @param int|float $jd The Julian day number input, default value at 1970-01-01T00:00+0000
     */
    public function __construct(private int | float $jd = 2440587.5) {}

    /**
     * Return Solar longitude degress value crresponding to Julian day number input
     *
     * @return float
     */
    public function getOutput(): float
    {
        $T = ($this->jd - 2451545) / 36525;
        $dr = M_PI / 180;
        $L = 280.460 + 36000.770 * $T;
        $G = 357.528 + 35999.050 * $T;
        $ec = 1.915 * sin($dr * $G) + 0.020 * sin($dr * 2 * $G);
        $lambda = $L + $ec;
        $L =  $lambda - 360 * floor($lambda / 360);

        return $this->toFixed($L);
    }
}
