<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarHourBeginNewDayHeavenlyStemConverter extends AbstractLunarHeavenlyStemConverter
{
    protected function _getOffset(): int
    {
        $offset          = 0;
        $compare         = 0;
        $dayHeavenlyStem = (new LunarDayHeavenlyStemConverter($this->datetime))->getTerm();

        while($compare != $dayHeavenlyStem->getOffset()) {
            $compare    = ($compare + 1) % 10;
            $offset     = ($offset + 2) % 10;      
        }

        return $offset;
    }
}