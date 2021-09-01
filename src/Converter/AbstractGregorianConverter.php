<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\DateTimeFormatter;

abstract class AbstractGregorianConverter
{
    protected $datetime;

    public function __construct(DateTimeFormatter $datetime)
    {
        $this->datetime = $datetime;

        // Do converting
        $this->_convert();
    }

    /**
     * This method should be uses inputs to converting and storing ouput propeties
     *
     * @return void
     */
    protected abstract function _convert(): void;
}