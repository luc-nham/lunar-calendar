<?php

namespace LucNham\LunarCalendar\Contracts;

use LucNham\LunarCalendar\Terms\SexagenaryMilestone;

/**
 * To format sexagenary milestone to human readable
 */
interface SexagenaryFormattable
{
    /**
     * Returns sexagenary terms formatted according to given format
     *
     * @param string $formatter
     * @param SexagenaryMilestone $terms
     * @return string
     */
    public function format(string $formatter, SexagenaryMilestone $terms): string;
}
