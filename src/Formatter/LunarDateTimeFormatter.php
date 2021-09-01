<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class LunarDateTimeFormatter extends DateTimeFormatter implements LunarDateTimeInterface
{
    public $leapMonth;
    public $jd;

    public static function create(): LunarDateTimeFormatter
    {
        return new LunarDateTimeFormatter();
    }

    public function setLeapmonth(bool $isLeapMonth): LunarDateTimeInterface
    {
        $this->leapMonth = $isLeapMonth;
        return $this;
    }

    public function setJd(float $jd): LunarDateTimeInterface
    {
        $this->jd = $jd;
        return $this;
    }

    public function isLeapMoth(): bool
    {
        return $this->leapMonth;
    }

    public function getJd(bool $includeDecimals = true): float
    {
        return ($includeDecimals)
                    ? $this->jd
                    : floor($this->jd);
    }
}