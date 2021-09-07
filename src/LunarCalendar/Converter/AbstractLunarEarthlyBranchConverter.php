<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\LunarEarthlyBranchFormatter;

abstract class AbstractLunarEarthlyBranchConverter extends AbstractLunarSexagenaryConverter
{
    public function getTerm(): LunarEarthlyBranchFormatter
    {
        $term = new LunarEarthlyBranchFormatter($this->_getOffset());
        return $term->setAttrsByOffset();
    }
}