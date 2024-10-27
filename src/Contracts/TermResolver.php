<?php

namespace LucNham\LunarCalendar\Contracts;

/**
 * To resolve term types used attribute
 */
interface TermResolver
{
    /**
     * Resolve single term
     *
     * @param mixed $value          Value of target attribute
     * @param string|null $name     Name of target attribute
     */
    public function resolve(mixed $value, ?string $name = null);

    /**
     * Resolve all terms in group
     *
     * @return array
     */
    public function resolveAll(): array;
}
