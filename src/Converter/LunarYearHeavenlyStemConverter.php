<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarYearHeavenlyStemConverter extends AbstractLunarHeavenlyStemConverter
{
    protected function _getOffset(): int
    {
        return ($this->datetime->getDate('Y') + 6) % 10;
    }
}