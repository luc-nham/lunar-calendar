<?php

namespace LucNham\LunarCalendar;

use DateTimeInterface;
use Exception;
use LucNham\LunarCalendar\Attributes\SolarTermAttribute;
use LucNham\LunarCalendar\Contracts\LunarDateTime;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToUnix;
use LucNham\LunarCalendar\Converters\UnixToJd;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;
use LucNham\LunarCalendar\Terms\SolarTermMilestone;
use ReflectionClass;

/**
 * Solar term resolver
 * 
 * @property string $key                    Solar term key
 * @property string $name                   Solar term name
 * @property int $position                  Solar term position in term group
 * @property float $ls                      The Solar longitude of beginning point of the term
 * @property string $type                   Classify between even and odd
 * @property SolarTermMilestone $begin      The milestone represents to beginning point
 * @property SolarTermMilestone $current    The milestone represents to current point
 */
class SolarTerm
{
    private float $jd;
    private float $angle;
    private SolarTermIdentifier $term;

    /**
     * Create new Solar term
     *
     * @param integer|null $time    Unix timestamp, default null mean current time
     * @param string $target        The identifier class to resolve terms, useful for localization. 
     *                              Default returns the English version of the term.
     */
    public function __construct(
        private ?int $time = null,
        private string $target = SolarTermIdentifier::class
    ) {
        if ($time === null) {
            $this->time = time();
        }

        $this->jd = (new UnixToJd($this->time))->getOutput();
        $this->angle = (new JdToLs($this->jd))->getOutput();
        $this->term = $this->resolveTerm((floor($this->angle / 15) + 3) % 24);
    }

    /**
     * Magic get
     */
    public function __get(string $name)
    {
        $term = $this->getTerm();

        $value = match ($name) {
            'key' => $term->key,
            'name' => $term->name,
            'position' => $term->position,
            'ls' => $term->ls,
            'type' => $term->type,
            'current' => $this->getCurrnetMilestone(),
            'begin' => $this->getBeginMilestone(),
            default => null
        };

        if ($value === null) {
            throw new Exception("Attribute '{$name}' does not exist");
        }

        return $value;
    }

    /**
     * Return target Solar term by position
     *
     * @param integer $position
     * @return SolarTermIdentifier
     */
    protected function resolveTerm(int $position): SolarTermIdentifier
    {
        try {
            $class = new ReflectionClass($this->target);
            $attributes = $class->getAttributes(SolarTermAttribute::class);


            foreach ($attributes as $att) {
                $instance = $att->newInstance();

                if ($instance->position === $position) {
                    return new $this->target(...(array)$instance);
                }
            }

            throw new Exception("The Solar term corresponding to position {$position} could not be found");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Returns current term identifier
     *
     * @return SolarTermIdentifier
     */
    public function getTerm(): SolarTermIdentifier
    {
        return $this->term;
    }

    /**
     * Return milestone representation of the beginning point of current Solar term
     *
     * @return SolarTermMilestone
     */
    public function getBeginMilestone(): SolarTermMilestone
    {
        $diff = (float)(round($this->angle, 5) - $this->ls);

        if ($diff <= 0.00001) {
            return new SolarTermMilestone(
                jd: $this->jd,
                angle: round($this->angle, 1),
            );
        }

        $jd = $this->jd;
        $angle = $this->angle;
        $nextAngle = $angle;

        do {
            $nextJd = $jd - $diff * 0.95;
            $nextAngle = (new JdToLs($nextJd))->getOutput();
            $diff = $nextAngle - $this->ls;

            // Not coveragable
            // if ($diff < 0) {
            //     break;
            // }

            $angle = $nextAngle;
            $jd = $nextJd;
        } while (round($diff, 5) > 0.00001);

        return new SolarTermMilestone(
            jd: round($jd, 7),
            angle: round($angle, 1)
        );
    }

    public function getCurrnetMilestone()
    {
        return new SolarTermMilestone(
            jd: $this->jd,
            angle: round($this->angle, 1)
        );
    }

    /**
     * Return new instance with attached information of previous Solar term
     * 
     * @return self
     */
    public function previous(): self
    {
        $time = (new JdToUnix($this->begin->jd - 14))->getOutput();

        return new self(
            time: $time,
            target: $this->target
        );
    }

    /**
     * Return new instance with attached information of next Solar term
     * 
     * @return self
     */
    public function next(): self
    {
        $time = (new JdToUnix($this->begin->jd + 17))->getOutput();

        return new self(
            time: $time,
            target: $this->target
        );
    }

    /**
     * Create instance from date time, support both Gregorian and Lunar
     */
    public static function fromDate(
        DateTimeInterface | LunarDateTime $date,
        string $target = SolarTermIdentifier::class
    ): self {
        return new self(
            time: $date->getTimestamp(),
            target: $target
        );
    }

    /**
     * Returns Solar term corresponding to current date time  
     *
     * @param string $target
     * @return self
     */
    // public static function now(string $target = SolarTermIdentifier::class): self
    // {
    //     return new self(
    //         time: time(),
    //         target: $target
    //     );
    // }
}
