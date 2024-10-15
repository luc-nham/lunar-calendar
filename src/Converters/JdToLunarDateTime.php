<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Contracts\LunarGuaranteedAccessible;
use LucNham\LunarCalendar\Converters\Traits\JdInputSetable;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;

/**
 * Converter that converts a Julian day number to Lunar date time period.
 */
class JdToLunarDateTime extends Converter implements LunarGuaranteedAccessible
{
    use JdInputSetable;

    /**
     * Create new converter
     *
     * @param float $jd         Julian day number input, default 2440623.5 is corresponding to Lunar
     *                          1970-01-01T00:00+0000
     * @param integer $offset   UTC offset in seconds, default 0 mean UTC
     */
    public function __construct(private float $jd = 2440623.5, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Get the Lunar month number
     *
     * @param NewMoonPhase $nm                          Current (input) new moon phase
     * @param NewMoonPhase $fnm                         Lunar first new moon phase
     * @param LunarLeapMonthNewMoonPhase|null $lnm      Lunar leap month new moon phase
     * @return int
     */
    protected function getMonthNumber(
        NewMoonPhase $nm,
        NewMoonPhase $fnm,
        ?LunarLeapMonthNewMoonPhase $lnm
    ): int {
        if ($nm->total === $fnm->total) {
            return 1;
        }

        $month = $nm->total - $fnm->total;

        if (!$lnm || $nm->total < $lnm->total) {
            return $month + 1;
        }

        return $month;
    }

    /**
     * Return Lunar date time period.
     *
     * @return LunarDateTimeGuaranteed
     */
    public function getOutput(): LunarDateTimeGuaranteed
    {
        $offset = $this->offset();
        $jd = $this->jd;
        $mjd = (new JdToMidnightJd($jd, $offset))->getOutput();
        $newMoon = (new JdToLunarNewMoon($mjd, $offset))->getOutput();
        $fistNewMoon = (new NewMoonToLunarFirstNewMoon($newMoon, $offset))->getOutput();
        $leapNewMoon = (new LunarFirstNewMoonToLunarLeapNewMoon($fistNewMoon, $offset))->getOutput();
        $time = (new JdToTime($this->jd, $offset))->getOutput();

        return new LunarDateTimeGuaranteed(
            d: floor($mjd - $newMoon->jd + 1),
            m: $this->getMonthNumber($newMoon, $fistNewMoon, $leapNewMoon),
            y: $fistNewMoon->year,
            l: $leapNewMoon?->month ?? 0,
            leap: $newMoon->total === $leapNewMoon?->total,
            h: $time->h,
            i: $time->i,
            s: $time->s,
            j: $this->jd,
        );
    }

    /**
     * @inheritDoc
     */
    public function getGuaranteedLunarDateTime(): LunarDateTimeGuaranteed
    {
        return $this->getOutput();
    }
}
