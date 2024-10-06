<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Converters\Traits\JdInputSetable;
use LucNham\LunarCalendar\Terms\NewMoonPhase;

/**
 * Converter that convert a Julian day number input to corresponding New moon phase
 */
class JdToNewMoon extends ToNewMoon
{
    use JdInputSetable;

    /**
     * Create new converter
     *
     * @param int|float $jd Julian day number input, default corresponding to 1900-01-01T00:00+0000
     */
    public function __construct(private int|float $jd = 2415020.5) {}

    /**
     * Return New moon phase properties. The Julian day number of New moon phase always smaller than 
     * the Julian day number input.
     *
     * @return NewMoonPhase
     */
    public function getOutput(): NewMoonPhase
    {
        $total = $this->total($this->jd);
        $jd = $this->truephase($total);

        return new NewMoonPhase($total, $this->toFixed($jd));
    }
}
