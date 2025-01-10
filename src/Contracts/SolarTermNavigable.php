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
     * @return SolarTermNavigable&SolarTermInterface
     */
    public function previous(): SolarTermNavigable&SolarTermInterface;

    /**
     * Move to next Solar term
     * 
     * @return SolarTermNavigable&SolarTermInterface
     */
    public function next(): SolarTermNavigable&SolarTermInterface;
}
