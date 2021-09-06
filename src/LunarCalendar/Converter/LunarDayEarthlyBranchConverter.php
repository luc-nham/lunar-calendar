<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarDayEarthlyBranchConverter extends AbstractLunarEarthlyBranchConverter
{
    protected function _getOffset(): int
    {
        return (floor($this->datetime->get('j')) + 1) % 12;
    }
}