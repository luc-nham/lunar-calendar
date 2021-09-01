<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarDayEarthlyBranchConverter extends AbstractLunarEarthlyBranchConverter
{
    protected function _getOffset(): int
    {
        return ($this->datetime->getJd(false) + 1) % 12;
    }
}