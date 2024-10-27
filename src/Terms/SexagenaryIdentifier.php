<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Stores basic identification information of a Sexagenary terms
 */
readonly abstract class SexagenaryIdentifier
{
    /**
     * A string or character to classify term
     *
     * @var string
     */
    public string $type;

    /**
     * Create Stem
     * 
     * @param string $key   Key of term
     * @param string $name  Display name
     * @param int $order    Position of the term in term group
     */
    public function __construct(
        public string $key,
        public string $name,
        public int $position,
    ) {
        $this->type = $this->registerType();
    }

    /**
     * Register the term type
     *
     * @return string
     */
    protected abstract function registerType(): string;
}
