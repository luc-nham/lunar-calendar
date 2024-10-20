<?php

namespace LucNham\LunarCalendar\Terms;

use Exception;
use LucNham\LunarCalendar\Converters\DateTimeIntervalToDateTimeString;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToUnix;

/**
 * Store a Solar term milestone
 * 
 * @property int $unix          Unix timestamp represents to milestone
 * @property string $datetime   Date time string with format 'Y-m-d H:i:s P' represents to milestone
 */
readonly class SolarTermMilestone
{
    /**
     * Create new term
     *
     * @param float $jd             Julian day number
     * @param float $angle          Angle number corresponding to Julian day number
     */
    public function __construct(
        public float $jd,
        public float $angle,
    ) {}

    /**
     * To get extra dynamic properties if needed
     *
     * @param string $name
     * @return void
     */
    public function __get(string $name)
    {
        $value = match ($name) {
            'unix' => (new JdToUnix($this->jd))->getOutput(),
            'datetime' => (new JdToGregorian($this->jd))
                ->then(DateTimeIntervalToDateTimeString::class)
                ->getOutput(),
            default => null
        };

        if ($value === null) {
            throw new Exception("Property '{$name}' dose not exists");
        }

        return $value;
    }
}
