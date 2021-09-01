<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

interface DateTimeInterface
{
    /**
     * Set date
     *
     * @param integer $d
     * @param integer $m
     * @param integer $Y
     * @return void
     */
    public function setDate(int $d = 0, int $m = 0, int $Y = 0): self;

    /**
     * Set time
     *
     * @param integer $H
     * @param integer $i
     * @param integer $s
     * @return self
     */
    public function setTime(int $H = 0, int $i = 0, int $s = 0): self;

    /**
     * Set time zone in float, ex '+0730' should be converter to 7.5
     *
     * @param float $timeZone
     * @return self
     */
    public function setTimeZone(float $timeZone): self;

    /**
     * Get date
     *
     * @param string $key
     * @return integer
     */
    public function getDate(string $key): int;

    /**
     * Get time
     *
     * @param string $key
     * @return integer
     */
    public function getTime(string $key): int;

    /**
     * Get time zone
     *
     * @return float
     */
    public function getTimeZone(): float;
}