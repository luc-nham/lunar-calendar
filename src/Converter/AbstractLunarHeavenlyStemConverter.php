<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\HeavenlyStemFormatter;
use LunarCalendar\Formatter\SexagenaryTermFormatter;

abstract class AbstractLunarHeavenlyStemConverter extends AbstractLunarSexagenaryConverter
{
    /**
     * The output of Converter
     *
     * @return SexagenaryTermFormatter
     */
    public function getTerm(): SexagenaryTermFormatter
    {
        return new HeavenlyStemFormatter($this->_getOffset());
    }
}