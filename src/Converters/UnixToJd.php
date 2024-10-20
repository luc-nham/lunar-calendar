<?php

namespace LucNham\LunarCalendar\Converters;

/**
 * Convert Unix timestamp to Julian day number
 */
class UnixToJd extends Converter
{
    /**
     * Create new converter
     *
     * @param integer $unix Unix timestamp, default 0 mean 1970-01-01 00:00 +00:00
     */
    public function __construct(private int $unix = 0) {}

    /**
     * Returns Julian day number corresponding to Unix timestamp input
     *
     * @return float
     */
    public function getOutput(): float
    {
        return $this->toFixed($this->unix / 86400 + 2440587.5);
    }
}
