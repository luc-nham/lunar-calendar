<?php

namespace LucNham\LunarCalendar\Contracts;

use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;

/**
 * Ensure to be able to receive a Lunar date time guaranteed object
 */
interface LunarGuaranteedAccessible
{
    /**
     * Returns a guaranteed lunar date time object
     *
     * @return LunarDateTimeGuaranteed
     */
    public function getGuaranteedLunarDateTime(): LunarDateTimeGuaranteed;
}
