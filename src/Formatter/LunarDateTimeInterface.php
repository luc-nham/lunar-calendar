<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

interface LunarDateTimeInterface extends DateTimeInterface
{
    /**
     * Set value to check if is leap month
     *
     * @param boolean $isLeapMonth
     * @return self
     */
    public function setLeapmonth(bool $isLeapMonth): self;

    /**
     * Set Julian Day Count of Lunar Date time
     *
     * @param float $jd
     * @return self
     */
    public function setJd(float $jd): self;

    /**
     * Check is leap month
     *
     * @return boolean
     */
    public function isLeapMoth():bool;

    /**
     * Get Julian day count
     *
     * @return float
     */
    public function getJd():float;
}