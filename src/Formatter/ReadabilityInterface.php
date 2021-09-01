<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

interface ReadabilityInterface
{
    /**
     * This method convert a format string to human readable string
     *
     * @param string $format    Ex: d-m-Y H:i:s, m/d/Y
     * @return string
     */
    public function format(string $format): string;
}