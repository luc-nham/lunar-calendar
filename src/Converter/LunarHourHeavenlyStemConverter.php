<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\HeavenlyStemFormatter;

class LunarHourHeavenlyStemConverter extends AbstractLunarHeavenlyStemConverter
{
    #[HeavenlyStemFormatter]
    protected $newDayBeginTerm;

    protected function _getOffset(): int
    {
        $hourBeginNewDay = new LunarHourBeginNewDayHeavenlyStemConverter($this->datetime);
        $offset          = $hourBeginNewDay->getTerm()->getOffset();
        $compareH        = 23;

        while($compareH != $this->datetime->getTime('H')) {
            $compareH = ($compareH + 1) % 24;
            
            if($compareH % 2 != 0) {
                $offset = ($offset + 1) % 10;
            }
        }

        return $offset;
    }

    public function getNewDayBeginTerm(): HeavenlyStemFormatter
    {
        if(null === $this->newDayBeginTerm) {
            $offset          = 0;
            $compare         = 0;
            $dayHeavenlyStem = (new LunarDayHeavenlyStemConverter($this->datetime))->getTerm();

            while($compare != $dayHeavenlyStem->getOffset()) {
                $compare    = ($compare + 1) % 10;
                $offset     = ($offset + 2) % 10;      
            }

            $this->newDayBeginTerm = new HeavenlyStemFormatter($offset);
        }

        return $this->newDayBeginTerm;
    }
}