<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarHourHeavenlyStemConverter extends AbstractLunarHeavenlyStemConverter
{
    protected function _getOffset(): int
    {
        $hourBeginNewDay = new LunarHourBeginNewDayHeavenlyStemConverter($this->datetime);
        $offset          = $hourBeginNewDay->getTerm()->getOffset();
        $compareH        = 23;

        while($compareH != $this->datetime->get('H')) {
            $compareH = ($compareH + 1) % 24;
            
            if($compareH % 2 != 0) {
                $offset = ($offset + 1) % 10;
            }
        }

        return $offset;
    }
}