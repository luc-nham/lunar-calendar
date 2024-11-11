<?php

namespace LucNham\LunarCalendar\Enums;

/**
 * Determine the duration calculation mode of Solar term
 */
enum SolarTermDurationMode
{
    /**
     * In strict mode, the calculation will use the time (hour, minute, second) value portion
     */
    case STRICT;

    /**
     * In normal mode, the calculation will ignore the time (hour, minute, second) value portion
     */
    case NORMAL;
}
