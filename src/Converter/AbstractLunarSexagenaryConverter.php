<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\LunarDateTimeFormatter;
use LunarCalendar\Formatter\SexagenaryTermFormatter;

abstract class AbstractLunarSexagenaryConverter
{
    protected $datetime;

    public function __construct(LunarDateTimeFormatter $lunarDatetime)
    {
        $this->datetime = $lunarDatetime;
    }

    /**
     * This method should return calculated offset of a sexagenary term
     *
     * @return integer
     */
    protected abstract function _getOffset(): int;

    /**
     * The output of Converter
     *
     * @return SexagenaryTermFormatter
     */
    public abstract function getTerm(): SexagenaryTermFormatter;
}