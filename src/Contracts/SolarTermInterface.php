<?php

namespace LucNham\LunarCalendar\Contracts;

use LucNham\LunarCalendar\Terms\SolarTermIdentifier;

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
