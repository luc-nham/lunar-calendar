<?php

namespace LucNham\LunarCalendar\Contracts;

use LucNham\LunarCalendar\Terms\SolarTermIdentifier;

/**
 * @property string $key    Solar term key
 * @property string $name   Solar term name
 * @property int $position  Solar term position in term group
 * @property float $ls      The Solar longitude of beginning point of the term
 * @property string $type   Classify between even and odd
 * @property float $angle   Solar longitude angle of current point
 * @property int $begin     Unix timestamp corresponds to beginning point
 */
interface SolarTermInterface
{
    /**
     * Returns current term identifier
     *
     * @return SolarTermIdentifier
     */
    public function getTerm(): SolarTermIdentifier;

    /**
     * Return timestamp corresponding to calculation point
     *
     * @return integer
     */
    public function getTimestamp(): int;

    /**
     * Return Solar longitude angle corresponding to calculation point
     *
     * @return float
     */
    public function getAngle(): float;

    /**
     * Return unix timestamp corresponding to beginning point
     *
     * @return int
     */
    public function getBeginTimestamp(): int;
}
