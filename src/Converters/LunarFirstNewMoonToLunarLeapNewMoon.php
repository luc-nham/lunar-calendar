<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;

/**
 * The converter to helps calculating leap month new moon phase from Lunar first new moon
 */
class LunarFirstNewMoonToLunarLeapNewMoon extends ToNewMoon
{
    public function __construct(private LunarFirstNewMoonPhase $nm, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Return the Leap month new moon phase if a lunar year be leap, otherwise return null.
     *
     * @return LunarLeapMonthNewMoonPhase|null
     */
    public function getOutput(): ?LunarLeapMonthNewMoonPhase
    {
        if (!$this->nm->leap) {
            return null;
        }

        $lsc = new JdToLs();
        $iterator = new NewMoonIterator($this->nm, $this->offset());
        $iterator->setKey(2);

        /** @var LunarLeapMonthNewMoonPhase[] */
        $result = [];
        $total = $this->nm->total + 1;
        $month = 1;
        $nm = $iterator->current();

        for ($i = 0; $i <= 9; $i++) {
            $total++;
            $month++;
            $iterator->next();

            $nextNm = $iterator->current();

            /** @var int */
            $ls1 = $lsc->setJd($nm->jd)
                ->forward(fn(float $deg) => floor($deg / 30));

            /** @var int */
            $ls2 = $lsc->setJd($nextNm->jd)
                ->forward(fn(float $deg) => floor($deg / 30));

            if ($ls1 === $ls2) {
                array_push(
                    $result,
                    new LunarLeapMonthNewMoonPhase(
                        total: $total,
                        jd: $nm->jd,
                        month: $month
                    )
                );
            }

            $nm = $nextNm;
        }

        $leap = null;

        foreach ($result as $nm) {
            if ($nm->month === 11) {
                $leap = $nm;
                break;
            }
        }

        return $leap ? $leap : $result[0];
    }
}
