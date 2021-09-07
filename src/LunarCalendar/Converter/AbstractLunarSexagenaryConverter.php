<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\LunarDateTimeStorageInterface;
use LunarCalendar\Formatter\TermInterface;

abstract class AbstractLunarSexagenaryConverter
{
    protected $datetime;

    public function __construct(LunarDateTimeStorageInterface $datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * Children should be find offset of Term and return it
     *
     * @return integer
     */
    protected abstract function _getOffset(): int;

    public abstract function getTerm(): TermInterface;
}