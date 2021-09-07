<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\LunarHeavenlyStemFormatter;

abstract class AbstractLunarHeavenlyStemConverter extends AbstractLunarSexagenaryConverter
{
    public function getTerm(): LunarHeavenlyStemFormatter
    {
        $term = new LunarHeavenlyStemFormatter($this->_getOffset());
        return $term->setAttrsByOffset();
    }
}