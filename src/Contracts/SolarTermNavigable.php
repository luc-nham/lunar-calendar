<?php

namespace LucNham\LunarCalendar\Contracts;

/**
 * Allow a Solar term class can move to next and privious terms
 */
interface SolarTermNavigable
{
    /**
     * Move to previous Solar term
     * 
     * @return SolarTermInterface
     */
    public function previous(): SolarTermInterface;

    /**
     * Move to next Solar term
     * 
     * @return SolarTermInterface
     */
    public function next(): SolarTermInterface;
}
