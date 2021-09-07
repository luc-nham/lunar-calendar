<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarMonthHeavenlyStemConverter extends AbstractLunarHeavenlyStemConverter
{
    protected function _getOffset(): int
    {
        return ($this->datetime->get('Y') * 12 + $this->datetime->get('m') + 3) % 10;
    }
}