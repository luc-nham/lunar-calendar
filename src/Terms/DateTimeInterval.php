<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * The class includes read-only properties that store time intervals, used to store output values ​​or 
 * provide as input to converters.
 */
readonly class DateTimeInterval
{
    public function __construct(
        public int $d = 1,
        public int $m = 1,
        public int $y = 1970,
        public int $h = 0,
        public int $i = 0,
        public int $s = 0,
    ) {}

    /**
     * Convert properties to valid PHP date string. Supported characters include: Y, m, d, H, i, s
     *
     * @param string $format
     * @return string
     */
    public function toString(string $format = 'Y-m-d H:i:s'): string
    {
        $pad = function (int $v, int $q = 2): string {
            return str_pad($v, $q, '0', STR_PAD_LEFT);
        };

        return strtr($format, [
            'Y' => $pad($this->y, 4),
            'm' => $pad($this->m),
            'd' => $pad($this->d),
            'H' => $pad($this->h),
            'i' => $pad($this->i),
            's' => $pad($this->s),
        ]);
    }
}
