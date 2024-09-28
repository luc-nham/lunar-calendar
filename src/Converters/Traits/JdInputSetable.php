<?php

namespace LucNham\LunarCalendar\Converters\Traits;

/**
 * The trait that makes it possible for converters to change the Julian day number input value.
 */
trait JdInputSetable
{
    /**
     * Set the Julian day number input
     *
     * @param integer|float $jd
     * @return self
     */
    public function setJd(int|float $jd): self
    {
        $this->jd = $jd;
        return $this;
    }
}
