<?php

namespace LucNham\LunarCalendar\Terms;

use DateTimeZone;

/**
 * Stores output for a Lunar string parser
 */
readonly class LunarParsingResults
{
    /**
     * Create new storage
     *
     * @param LunarDateTimeInterval $interval   Lunar date time interval parsed
     * @param DateTimeZone $timezone            Timezone
     * @param integer $offset                   UTC offset in seconds
     * @param array $warnings                   Parsing warings
     * @param integer $warning_count            Parsing waring count
     * @param array $errors                     Parsing errors
     * @param integer $error_count              Parsing error count
     */
    public function __construct(
        public LunarDateTimeInterval $interval,
        public DateTimeZone $timezone,
        public int $offset,
        public array $warnings = [],
        public int $warning_count = 0,
        public array $errors = [],
        public int $error_count = 0
    ) {}
}
