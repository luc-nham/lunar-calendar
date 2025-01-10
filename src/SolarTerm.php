<?php

namespace LucNham\LunarCalendar;

use DateTimeInterface;
use Exception;
use LucNham\LunarCalendar\Contracts\LunarDateTime;
use LucNham\LunarCalendar\Contracts\SolarTermInterface;
use LucNham\LunarCalendar\Contracts\SolarTermNavigable;
use LucNham\LunarCalendar\Contracts\TermResolver;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\UnixToJd;
use LucNham\LunarCalendar\Resolvers\SolarTermResolver;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;

/**
 * Solar term resolver
 */
class SolarTerm implements SolarTermInterface, SolarTermNavigable
{
    /**
     * Current Solar longitude angle number
     *
     * @var float
     */
    private float $angle;

    /**
     * Current target term
     *
     * @var SolarTermIdentifier
     */
    private SolarTermIdentifier $term;

    /**
     * Solar terms resolver
     *
     * @var TermResolver
     */
    private TermResolver $resolver;

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
        $this->resolver = $this->createTermResolver();

        if ($time === null) {
            $this->time = time();
        }

        $angle = (new UnixToJd($this->time))
            ->then(JdToLs::class)
            ->forward(fn(float $angle) => round($angle, 3));

        $position = (floor($angle / 15) + 3) % 24;

        $this->angle = round($angle, 3);
        $this->term = $this->resolve($position);
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
            'angle' => $this->angle,
            'begin' => $this->getBeginTimestamp(),
            default => null
        };

        if ($value === null) {
            throw new Exception("Attribute '{$name}' does not exist");
        }

        return $value;
    }

    /**
     * Return Solar term resolver
     *
     * @return TermResolver
     */
    protected function createTermResolver(): TermResolver
    {
        $resolver = new SolarTermResolver();
        $resolver->setTargetTermClass($this->target);

        return $resolver;
    }

    /**
     * Resolve Solar term via it's position
     *
     * @param integer $position
     * @return SolarTermIdentifier
     */
    protected function resolve(int $position): SolarTermIdentifier
    {
        return $this->resolver->resolve($position, 'position');
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
     * @inheritDoc
     *
     * @return integer
     */
    public function getBeginTimestamp(): int
    {
        $diff = $this->angle - $this->ls;

        if ($diff <= 0.001) {
            return $this->time;
        }

        $time = $this->time;
        $angle = $this->angle;
        $nextAngle = $angle;
        $prevDiff = 360;

        do {
            $nextTime = $time - $diff * 0.9 * 86400;
            $nextAngle = (new UnixToJd((int)$nextTime))
                ->then(JdToLs::class)
                ->getOutput();

            $diff = $nextAngle - $this->ls;

            if ($diff < 0.001 || $diff > $prevDiff) {
                break;
            }

            $angle = $nextAngle;
            $time = $nextTime;
            $prevDiff = $diff;
        } while ($diff > 0.001);

        return floor($time);
    }

    /**
     * @inheritDoc
     */
    public function previous(): SolarTermInterface&SolarTermNavigable
    {
        return new self(
            time: (new self($this->begin - 14 * 86400))->begin,
            target: $this->target
        );
    }

    /**
     * @inheritDoc
     */
    public function next(): SolarTermInterface&SolarTermNavigable
    {
        return new self(
            time: (new self($this->begin + 17 * 86400))->begin,
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
    public static function now(string $target = SolarTermIdentifier::class): self
    {
        return new self(
            time: time(),
            target: $target
        );
    }

    /**
     * @inheritDoc
     */
    public function getTimestamp(): int
    {
        return $this->time;
    }

    /**
     * @inheritDoc
     */
    public function getAngle(): float
    {
        return $this->angle;
    }
}
