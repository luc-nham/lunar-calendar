<?php

namespace LucNham\LunarCalendar\Terms;

use LucNham\LunarCalendar\Enums\SolarTermDurationMode;

/**
 * Store a Solar term duration
 */
readonly class SolarTermDurationStorage
{
    /**
     * Create storage
     *
     * @param integer|float $total          Total days of term
     * @param integer|float $passed         Days have been passed
     * @param integer|float $remain         Days remain
     * @param SolarTermDurationMode $mode   Calculation mode
     */
    public function __construct(
        public int|float $total,
        public int|float $passed,
        public int|float $remain,
        public SolarTermDurationMode $mode,
    ) {}
}
