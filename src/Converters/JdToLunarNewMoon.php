<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Converters\Traits\JdInputSetable;
use LucNham\LunarCalendar\Terms\NewMoonPhase;

/**
 * Converter that convert a Julian day number input to corresponding New moon phase, used as a 
 * reference point for Lunar date formatting.
 */
class JdToLunarNewMoon extends ToNewMoon
{
    use JdInputSetable;

    /**
     * Create new converter
     *
     * @param int|float $jd Julian day number input, default corresponding to 1900-01-01T00:00+0000
     * @param int $offset   Timezone offset
     */
    public function __construct(private int|float $jd = 2415020.5, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Return New moon phase properties
     *
     * @return NewMoonPhase
     */
    public function getOuput(): NewMoonPhase
    {
        $offset = $this->offset();
        $localMjd = (new JdToMidnightJd($this->jd, $offset))->getOuput();

        $diff = $this->toFixed($offset / 3600 / 24);

        $total = $this->total($localMjd + $diff);
        $nmJd = $this->truephase($total);
        $nmMjd = (new JdToMidnightJd($nmJd, $offset))->getOuput();

        return new NewMoonPhase($total, $this->toFixed($nmMjd));
    }
}
