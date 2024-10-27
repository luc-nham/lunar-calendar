<?php

namespace LucNham\LunarCalendar\Resolvers;

use LucNham\LunarCalendar\Attributes\SolarTermAttribute;
use LucNham\LunarCalendar\Terms\SolarTermIdentifier;

/**
 * To resolve terms of Solar term system
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

    /**
     * Resolve a Solar term
     *
     * @param mixed $value
     * @param string|null $name
     * @return SolarTermIdentifier
     */
    public function resolve(mixed $value, ?string $name = null): SolarTermIdentifier
    {
        return parent::resolve($value, $name);
    }

    /**
     * Resolve all Solar terms
     *
     * @return SolarTermIdentifier[]
     */
    public function resolveAll(): array
    {
        return parent::resolveAll();
    }
}
