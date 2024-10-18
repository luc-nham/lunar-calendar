<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\DateTimeInterval;

/**
 * Converts a gregorian date time interval to string
 */
class DateTimeIntervalToDateTimeString extends Converter
{
    /**
     * Create new converter
     *
     * @param DateTimeInterval $interval    Interval
     * @param integer $offset               Timezone offset inseconds to get local formatted string
     */
    public function __construct(
        private DateTimeInterval $interval = new DateTimeInterval(),
        int $offset = 0
    ) {
        $this->setOffset($offset);
    }

    /**
     * Returns difference to Greenwich time (GMT) with colon between hours and minutes
     *
     * @param integer $offset
     * @return string
     */
    protected function getFormattedOffset(int $offset): string
    {
        $h = $offset / 3600;
        $d = abs($h - floor($h));

        $h = ($h >= 0)
            ? str_pad($h, 3, '+0', STR_PAD_LEFT)
            : str_pad(abs($h), 3, '-0', STR_PAD_LEFT);

        return $h . ':' . str_pad($d, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Return date time string with a popular and convenient form for use: 'Y-m-d H:i:s P'
     *
     * @return string
     */
    public function getOutput(): string
    {
        $date = $this->interval;

        $str = implode('-', [
            str_pad($date->y, 4, '0', STR_PAD_LEFT),
            str_pad($date->m, 2, '0', STR_PAD_LEFT),
            str_pad($date->d, 2, '0', STR_PAD_LEFT),
        ]);

        $str .= ' ';
        $str .= implode(':', [
            str_pad($date->h, 2, '0', STR_PAD_LEFT),
            str_pad($date->i, 2, '0', STR_PAD_LEFT),
            str_pad($date->s, 2, '0', STR_PAD_LEFT),
        ]);

        $str .= ' ';
        $str .= $this->getFormattedOffset($this->offset());

        return $str;
    }
}
