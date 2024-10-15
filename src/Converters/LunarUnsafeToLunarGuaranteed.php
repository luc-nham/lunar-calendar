<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Contracts\LunarGuaranteedAccessible;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;

/**
 * Correct unsafe Lunar date time input to safe Lunar date time output.
 */
class LunarUnsafeToLunarGuaranteed extends Converter implements LunarGuaranteedAccessible
{
    /**
     * Create new converter
     *
     * @param LunarDateTimeInterval $lunar  Lunar date time interval
     * @param integer $offset               Timezone offset in seconds, default 0 mean UTC
     */
    public function __construct(private LunarDateTimeInterval $lunar, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Return corrected lunar date time:
     * - Fix incorrect date and time input. For example, if input day is 35, the output with be 5 or
     *   6 depends on total days of lunar month, also the month number output will be increase by 1.
     * - Fix incorrect leap month input
     *
     * @return LunarDateTimeGuaranteed
     */
    public function getOutput(): LunarDateTimeGuaranteed
    {
        return (new LunarDateTimeToJd(
            lunar: $this->lunar,
            offset: $this->offset()
        ))
            ->then(JdToLunarDateTime::class)
            ->getOutput();
    }

    /**
     * @inheritDoc
     */
    public function getGuaranteedLunarDateTime(): LunarDateTimeGuaranteed
    {
        return $this->getOutput();
    }
}
