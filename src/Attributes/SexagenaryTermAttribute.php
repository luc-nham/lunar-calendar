<?php

namespace LucNham\LunarCalendar\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
readonly class SexagenaryTermAttribute
{
    /**
     * @param string $key   Key of term
     * @param string $name  Display name
     * @param int $order    Position of the term in term group
     */
    public function __construct(
        public string $key,
        public string $name,
        public int $position,
    ) {}
}
