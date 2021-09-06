<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarMonthEarthlyBranchConverter extends AbstractLunarEarthlyBranchConverter
{
    protected function _getOffset(): int
    {
        return ($this->datetime->get('m') + 1) % 12;
    }
}