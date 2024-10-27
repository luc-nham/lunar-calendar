<?php

namespace LucNham\LunarCalendar\Resolvers;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;
use LucNham\LunarCalendar\Terms\BranchIdentifier;

/**
 * Branch terms resolver
 * 
 * @method BranchIdentifier resolve(mixed $value, ?string $name = null) Resolve single Branch term
 * @method BranchIdentifier[] resolveAll() Resolve all Branch terms
 */
class BranchTermResolver extends BaseTermResolver
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
        return BranchIdentifier::class;
    }
}
