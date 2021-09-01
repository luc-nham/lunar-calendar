<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class LunarHourEarthlyBranchConverter extends AbstractLunarEarthlyBranchConverter
{
    protected function _getOffset(): int
    {
        $compareH   = 23;   // Lunar new day start at 23:00 
        $offset     = 0;    // 0 is Rat/Tý/Zǐ

        while($compareH != $this->datetime->getTime('H')) {
            $compareH = ($compareH + 1) % 24;
            
            if($compareH % 2 != 0) {
                $offset = ($offset + 1) % 12;
            }
        }

        return $offset;
    }
}