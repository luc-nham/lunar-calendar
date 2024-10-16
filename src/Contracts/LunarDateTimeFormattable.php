<?php

namespace LucNham\LunarCalendar\Contracts;

/**
 * Lunar date time string format
 */
interface LunarDateTimeFormattable
{
    /**
     * Returns date formatted according to given format
     *
     * @param string $formatter A formater string to get coreressponding Lunar date time string 
     *                          output such as 'd/m/Y H:i:s', 'Y-m-d'...
     * @return string
     */
    public function format(string $formatter): string;
}
