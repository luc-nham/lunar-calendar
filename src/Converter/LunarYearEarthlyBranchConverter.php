<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarYearEarthlyBranchConverter extends AbstractLunarEarthlyBranchConverter
{
    protected function _getOffset(): int
    {
        return ($this->datetime->getDate('Y') + 8) % 12;
    }
}