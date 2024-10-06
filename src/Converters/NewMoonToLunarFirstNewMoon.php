<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;

class NewMoonToLunarFirstNewMoon extends ToNewMoon
{
    public function __construct(private NewMoonPhase $nm, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Check if a lunar year be leap year
     *
     * @param integer $year Lunar year number to check
     * @return boolean      Return true if lunar year be leap, false if not
     */
    protected function isLunarYearBeLeap(int $year): bool
    {
        return in_array($year % 19, [0, 3, 6, 9, 11, 14, 17]);
    }


    public function getOutput(): LunarFirstNewMoonPhase
    {
        $gre = (new JdToGregorian(
            $this->nm->jd,
            $this->offset()
        ))->getOutput();

        $diff = $gre->y - 1900;
        $sub = $diff / 19;
        $absSub = abs($sub);
        $total = round($diff * 12 + $sub * 7 + 1);
        $year = $gre->y;

        /**
         * In each of the 19 lunar years, when encountering non-leap years with indexes 2, 5, and 13,
         * they need to be corrected to get correct results by adding one phase.
         */
        $diff = round($absSub - floor($absSub), 2);
        $correction = [0.05, 0.63, 0.21];

        if (in_array($diff, $correction)) {
            $total += 1;
        }

        if ($this->nm->total < $total) {
            if ($gre->m !== 12) {
                $year -= 1;
            }

            $total -= $this->isLunarYearBeLeap($year) ? 13 : 12;
        }

        return new LunarFirstNewMoonPhase(
            $total,
            (new JdToMidnightJd($this->truephase($total), $this->offset()))->getOutput(),
            $year,
            $this->isLunarYearBeLeap($year)
        );
    }
}
