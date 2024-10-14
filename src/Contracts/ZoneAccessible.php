<?php

namespace LucNham\LunarCalendar\Contracts;

use DateTimeZone;

interface ZoneAccessible
{
    public function getTimezone(): DateTimeZone;
}
