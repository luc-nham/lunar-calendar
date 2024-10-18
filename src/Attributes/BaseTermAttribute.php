<?php

namespace LucNham\LunarCalendar\Attributes;

use Attribute;

#[Attribute]
/**
 * Attributes for objects in the same group are not yet classified.
 */
readonly class BaseTermAttribute
{
    /**
     * Create new base term
     *
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
