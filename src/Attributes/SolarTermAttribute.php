<?php

namespace LucNham\LunarCalendar\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
readonly class SolarTermAttribute
{
    /**
     * @param string $key   Key of term
     * @param string $name  Display name
     * @param int $position Position of the term in term group
     * @param float $ls     The solar longitude angle corresponds to the starting point
     */
    public function __construct(
        public string $key,
        public string $name,
        public int $position,
        public float $ls,
    ) {}
}
