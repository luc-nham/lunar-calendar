<?php

namespace LucNham\LunarCalendar\Resolvers;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Terms\StemIdentifier;

/**
 * Stem term resolver
 * 
 * @method StemIdentifier resolve(mixed $value, ?string $name = null) Resolve single Stem term
 * @method StemIdentifier[] resolveAll() Resolve all Stem terms
 */
class StemTermResolver extends BaseTermResolver
{
    /**
     * @inheritDoc
     */
    public function getTargetAttributeClass(): string
    {
        return SexagenaryTermAttribute::class;
    }

    /**
     * @inheritDoc
     */
    public function getTargetTermClass(): string
    {
        return StemIdentifier::class;
    }
}
