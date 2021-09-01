<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\EarthlyBranchFormatter;
use LunarCalendar\Formatter\SexagenaryTermFormatter;

abstract class AbstractLunarEarthlyBranchConverter extends AbstractLunarSexagenaryConverter
{
    /**
     * The output of Converter
     *
     * @return SexagenaryTermFormatter
     */
    public function getTerm(): SexagenaryTermFormatter
    {
        return new EarthlyBranchFormatter($this->_getOffset());
    }
}