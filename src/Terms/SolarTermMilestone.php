<?php

namespace LucNham\LunarCalendar\Terms;

enum SolarTermEvent
{
    /**
     * The beginning milestone of Solar term
     */
    case BEGIN;

    /**
     * The current milestone of Solar term
     */
    case CURRENT;

    /**
     * The unknown milestone of Solar term
     */
    case ANY;
}

/**
 * Store a Solar term milestone
 */
readonly class SolarTermMilestone extends SolarLongitudeMileStone
{
    /**
     * Create new term
     *
     * @param float $jd             Julian day number
     * @param string $datetime      Date time string corresponding to Julian day number
     * @param float $angle          Angle number corresponding to Julian day number
     * @param [type] $event         Event key, default SolarTermEvent::ANY
     * @param string $descrition    A description for event
     */
    public function __construct(
        public float $jd,
        public string $datetime,
        public float $angle,
        public SolarTermEvent $event = SolarTermEvent::ANY,
        public string $descrition = '',
    ) {}

    /**
     * Returns description of milestone event. 
     *
     * @return string
     */
    public function getDescription(): string
    {
        if ($this->descrition) {
            return $this->descrition;
        }

        return match ($this->event) {
            SolarTermEvent::BEGIN => 'The beginning milestone of the term',
            SolarTermEvent::CURRENT => 'The current milestone of the term',
            SolarTermEvent::ANY => "Unknown milestone"
        };
    }
}
