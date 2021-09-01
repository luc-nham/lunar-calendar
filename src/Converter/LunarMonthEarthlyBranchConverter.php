<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarMonthEarthlyBranchConverter extends AbstractLunarEarthlyBranchConverter
{
    protected function _getOffset(): int
    {
        return ($this->datetime->getDate('m') + 1) % 12;
    }
}