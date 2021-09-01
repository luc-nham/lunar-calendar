<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class GregorianToJulianConverter extends AbstractGregorianConverter
{
    protected $jd;

    public function getJd($includeDecimal = true): float
    {
        $result = $this->jd;

        return ($includeDecimal)
                    ? $result
                    : floor($result);
    }
    
    protected function _convert(): void
    {
        $a      = floor((14 - $this->datetime->getDate('m')) / 12);
        $y      = $this->datetime->getDate('Y') + 4800 - $a;
        $m      = $this->datetime->getDate('m') + 12 * $a - 3;
        $jd     = $this->datetime->getDate('d') + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
        
        if ($jd < 2299161) {
            $jd = $this->datetime->getDate('d') + floor((153* $m + 2)/5) + 365 * $y + floor($y / 4) - 32083;
        }

        $jd += ($this->datetime->getTime('H') + $this->datetime->getTime('i') / 60 + $this->datetime->getTime('s') / 3600) / 24;
        
        $this->jd = $jd;
    }
}