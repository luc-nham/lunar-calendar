<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;

/**
 * Converts a guaranteed Lunar date time to Gregorian with format 'Y-m-d H:i:s P'
 */
class LunarDateTimeToDateTimeString extends Converter
{
    public function __construct(private LunarDateTimeInterval $lunar, int $offset = 0)
    {
        $this->setOffset($offset);
    }

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
        $date = (new LunarDateTimeToGregorian(
            lunar: $this->lunar,
            offset: $this->offset(),
        ))->getOutput();

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
