<?php

namespace LucNham\LunarCalendar\Resolvers;

use LucNham\LunarCalendar\Attributes\SolarTermAttribute;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;

/**
 * Solar terms resolver
 * 
 * @method SolarTermIdentifier resolve(mixed $value, ?string $name = null) Resolve single Solar term
 * @method SolarTermIdentifier[] resolveAll() Resolve all Solar terms
 */
class SolarTermResolver extends BaseTermResolver
{
    /**
     * @inheritDoc
     */
    public function getTargetAttributeClass(): string
    {
        return SolarTermAttribute::class;
    }

    /**
     * @inheritDoc
     */
    public function getTargetTermClass(): string
    {
        return SolarTermIdentifier::class;
    }
}
