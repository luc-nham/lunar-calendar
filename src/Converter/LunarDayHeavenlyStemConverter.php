<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarDayHeavenlyStemConverter extends AbstractLunarHeavenlyStemConverter
{
    protected function _getOffset(): int
    {
        return ($this->datetime->getJd(false) + 9) % 10;
    }
}