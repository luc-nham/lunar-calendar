<?php

namespace LucNham\LunarCalendar\Terms;

readonly class LunarFirstNewMoonPhase
{
    public function __construct(public int $total, public int | float $jd, public int $year, public bool $leap) {}
}
