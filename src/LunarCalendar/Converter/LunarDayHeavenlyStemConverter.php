<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarDayHeavenlyStemConverter extends AbstractLunarHeavenlyStemConverter
{
    protected function _getOffset(): int
    {
        return ((floor($this->datetime->get('j'))) + 9) % 10;
    }
}