<?php

namespace LucNham\LunarCalendar;

use DateTime;
use Exception;
use LucNham\LunarCalendar\Contracts\SolarTermInterface;
use LucNham\LunarCalendar\Contracts\SolarTermNavigable;
use LucNham\LunarCalendar\Enums\SolarTermDurationMode;
use LucNham\LunarCalendar\Terms\SolarTermDurationStorage;

/**
 * Calculate a Solar term duration
 * 
 * @property integer|float $total          Total days of term
 * @property integer|float $passed         Days have been passed
 * @property integer|float $remain         Days remain
 * @property SolarTermDurationMode $mode   Calculation mode
 * 
 * @method integer|float getTotal()         Return total days of term
 * @method integer|float getPassed()        Return days have been passed
 * @method integer|float getRemain()        Return days remain
 * @method SolarTermDurationMode getMode()  Return calculation mode
 */
class SolarTermDuration
{
    private SolarTermDurationStorage $duration;

    public function __construct(
        private SolarTermInterface&SolarTermNavigable $term,
        private SolarTermDurationMode $mode = SolarTermDurationMode::NORMAL
    ) {
        $this->duration = $this->getDuration();
    }

    /**
     * Access duration property
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (!property_exists($this->duration, $name)) {
            throw new Exception("Property '{$name}' dose not exists");
        }

        return $this->duration->{$name};
    }

    /**
     * Simple magic methods call
     *
     * @param mixed $name
     * @param mixed $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        return match ($name) {
            'getTotal' => $this->duration->total,
            'getPassed' => $this->duration->passed,
            'getRemain' => $this->duration->remain,
            'getMode' => $this->duration->mode,
        };
    }

    /**
     * Calculate duration
     *
     * @return SolarTermDurationStorage
     */
    protected function getDuration(): SolarTermDurationStorage
    {
        $begin = $this->term->getBeginTimestamp();
        $current = $this->term->getTimestamp();
        $nextBegin = $this->term->next()->getBeginTimestamp();
        $isNormal = $this->mode === SolarTermDurationMode::NORMAL;

        if ($isNormal) {
            $date = new DateTime('now');

            $begin = $date
                ->setTimestamp($begin)
                ->setTime(0, 0, 0)
                ->getTimestamp();

            $current = $date
                ->setTimestamp($current)
                ->setTime(0, 0, 0)
                ->getTimestamp();

            $nextBegin = $date
                ->setTimestamp($nextBegin)
                ->setTime(0, 0, 0)
                ->getTimestamp();
        }

        $total = ($nextBegin - $begin) / 86400;
        $passed = ($current - $begin) / 86400;
        $remain = $total - $passed;

        $fixed = 7;

        return new SolarTermDurationStorage(
            total: $isNormal ? (int)$total : round($total, $fixed),
            passed: $isNormal ? (int)$passed : round($passed, $fixed),
            remain: $isNormal ? (int)$remain : round($remain, $fixed),
            mode: $this->mode,
        );
    }

    /**
     * Return array
     *
     * @return array{total: int|float, passed: int|float, remain: int|float, mode: SolarTermDurationMode}
     */
    public function toArray(): array
    {
        return (array)$this->duration;
    }
}
