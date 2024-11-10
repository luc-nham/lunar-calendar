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
     * Return unix timestamp corresponding to beginning point
     *
     * @return int
     */
    public function getBeginTimestamp(): int;

    /**
     * Return new instance with attached information of previous Solar term
     * 
     * @return self
     */
    public function previous(): self;

    /**
     * Return new instance with attached information of next Solar term
     * 
     * @return self
     */
    public function next(): self;
}
