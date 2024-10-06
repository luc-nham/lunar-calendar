<?php

namespace LucNham\LunarCalendar\Converters;

use Closure;
use LucNham\LunarCalendar\Contracts\Converter as ContractsConverter;

/**
 * Base converter
 */
abstract class Converter implements ContractsConverter
{
    /**
     * Fixed number of decimal places for output
     */
    private int $fixed = 7;

    /**
     * Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for 
     * those east of UTC is always positive.
     *
     * @var integer
     */
    private int $offset = 0;

    /**
     * Set the fixed decimal places ouput
     *
     * @param integer $num
     * @return self
     */
    public function setFixed(int $num): self
    {
        $this->fixed = $num;
        return $this;
    }

    /**
     * Set timezone offset
     *
     * @param integer $offset
     * @return self
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Fixed a float number
     *
     * @param float $num
     * @return float
     */
    protected function toFixed(float $num): float
    {
        return round($num, $this->fixed);
    }

    /**
     * Return timezone offset in seconds
     *
     * @return integer
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @inheritDoc
     */
    public function forward(Closure $cb)
    {
        return $cb($this->getOutput());
    }
}
