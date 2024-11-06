<?php

namespace LucNham\LunarCalendar\Formatters;

use DateTimeZone;
use LucNham\LunarCalendar\Contracts\LunarDateTimeFormattable;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;

/**
 * Default lunar date time formatter
 */
class LunarDateTimeDefaultFormatter implements LunarDateTimeFormattable
{
    public function __construct(
        private LunarDateTimeGuaranteed $lunar,
        private DateTimeZone $timezone,
        private int $offset
    ) {}

    /**
     * 't' formated
     *
     * @return void
     */
    protected function t()
    {
        $newMoon = (new JdToLunarNewMoon(
            jd: $this->lunar->j,
            offset: $this->offset
        ))->getOutput();

        $navigator = new NewMoonIterator($newMoon, $this->offset);
        $navigator->next();

        $nextNm = $navigator->getOutput();

        return $nextNm->jd - $newMoon->jd;
    }

    /**
     * 'P' formated
     *
     * @return string
     */
    protected function P(): string
    {
        $h = $this->offset / 3600;
        $d = abs($h - floor($h));

        $h = ($h >= 0)
            ? str_pad($h, 3, '+0', STR_PAD_LEFT)
            : str_pad(abs($h), 3, '-0', STR_PAD_LEFT);

        $p = $h . ':' . str_pad($d, 2, '0', STR_PAD_LEFT);

        return $p;
    }

    /**
     * 'c' formated
     *
     * @return string
     */
    protected function c(): string
    {
        $str = implode('-', [
            $this->getEquivalent('Y'),
            $this->getEquivalent('m'),
            $this->getEquivalent('d')
        ]);

        $str .= 'T';
        $str .= implode(':', [
            $this->getEquivalent('H'),
            $this->getEquivalent('i'),
            $this->getEquivalent('s')
        ]);

        $str .= $this->getEquivalent('P');
        $str .= $this->getEquivalent('k');

        return rtrim($str);
    }

    /**
     * Get equivalent value of format character
     *
     * @param string $char
     * @return string
     */
    protected function getEquivalent(string $char): string
    {
        $equivalent = match ($char) {
            // Day of the month without leading zeros
            'j' => $this->lunar->d,

            // Day of the month, 2 digits with leading zeros
            'd' => str_pad($this->lunar->d, 2, '0', STR_PAD_LEFT),

            // Numeric representation of a month with leap sign, without leading zeros
            'l' => $this->lunar->leap ? $this->lunar->m . '+' : $this->lunar->m,

            // Numeric representation of a month with leap sign, with leading zeros
            'L' => $this->lunar->leap ? $this->getEquivalent('m') . '+' : $this->getEquivalent('m'),

            // Numeric representation of a month, without leading zeros and leap sign
            'n' => $this->lunar->m,

            // Numeric representation of a month, with leading zeros and leap sign
            'm' => str_pad($this->lunar->m, 2, '0', STR_PAD_LEFT),

            // A full numeric representation of a year, at least 4 digits
            'Y' => str_pad($this->lunar->y, 4, '0', STR_PAD_LEFT),

            // Total days of current month
            't' => $this->t(),

            // 24-hour format of an hour without leading zeros
            'G' => $this->lunar->h,

            // 12-hour format of an hour without leading zeros
            'g' => $this->lunar->h > 12 ? $this->lunar->h % 12 : $this->lunar->h,

            // 24-hour format of an hour with leading zeros
            'H' => str_pad($this->lunar->h, 2, '0', STR_PAD_LEFT),

            // 12-hour format of an hour with leading zeros
            'h' => str_pad($this->getEquivalent('g'), 2, '0', STR_PAD_LEFT),

            // Minutes with leading zeros
            'i' => str_pad($this->lunar->i, 2, '0', STR_PAD_LEFT),

            // Seconds with leading zeros
            's' => str_pad($this->lunar->s, 2, '0', STR_PAD_LEFT),

            // Lowercase Ante meridiem and Post meridiem
            'a' => $this->lunar->h < 12 ? 'am' : 'pm',

            // Uppercase Ante meridiem and Post meridiem
            'A' => $this->lunar->h < 12 ? 'AM' : 'PM',

            // Difference to Greenwich time (GMT) with colon between hours and minutes
            'P' => $this->P(),

            // Difference to Greenwich time (GMT) without colon between hours and minutes
            'O' => str_replace(':', '', $this->P()),

            // Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
            'U' => floor(($this->lunar->j - 2440587.5) * 86400),

            // Timezone offset in seconds. The offset for timezones west of UTC is always negative, 
            // and for those east of UTC is always positive.
            'Z' => $this->offset,

            // Leap month sign (+)
            'k' => $this->lunar->leap ? '(+)' : '',

            // Leap month sign [-]
            'K' => $this->lunar->leap ? '[+]' : '',

            // Timezone identifier
            'e' => $this->timezone->getName(),

            // A lunar ISO 8601 date version 
            'c' => $this->c(),

            default => $char
        };

        return (string)$equivalent;
    }

    /**
     * @inheritDoc
     */
    public function format(string $formatter): string
    {
        $result = $formatter;

        foreach (str_split($formatter) as $char) {
            $result = str_replace($char, $this->getEquivalent($char), $result);
        }

        return trim($result);
    }
}
