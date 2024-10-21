<?php

namespace LucNham\LunarCalendar\Terms;

use Exception;
use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;

#[SexagenaryTermAttribute(key: 'ty', name: 'Ty', position: 0)]
#[SexagenaryTermAttribute(key: 'suu', name: 'Suu', position: 1)]
#[SexagenaryTermAttribute(key: 'dan', name: 'Dan', position: 2)]
#[SexagenaryTermAttribute(key: 'mao', name: 'Mao', position: 3)]
#[SexagenaryTermAttribute(key: 'thin', name: 'Thin', position: 4)]
#[SexagenaryTermAttribute(key: 'ti', name: 'Ti', position: 5)]
#[SexagenaryTermAttribute(key: 'ngo', name: 'Ngo', position: 6)]
#[SexagenaryTermAttribute(key: 'mui', name: 'Mui', position: 7)]
#[SexagenaryTermAttribute(key: 'than', name: 'Than', position: 8)]
#[SexagenaryTermAttribute(key: 'dau', name: 'Dau', position: 9)]
#[SexagenaryTermAttribute(key: 'tuat', name: 'Tuat', position: 10)]
#[SexagenaryTermAttribute(key: 'hoi', name: 'Hoi', position: 11)]
readonly class BranchIdentifier extends SexagenaryIdentifier
{
    /**
     * @inheritDoc
     */
    protected function registerType(): string
    {
        return 'B';
    }

    /**
     * Returns the Branch term identification
     * 
     * @param string|int $term The term key, name or position
     * @param string $target Target class name
     * @return BranchIdentifier
     */
    public static function resolve(
        string|int $term,
        string $target = BranchIdentifier::class,
    ): BranchIdentifier {
        try {
            return parent::resolve($term, $target);
        } catch (\Throwable $th) {
            $propName = is_string($term) ? 'name or key' : 'position';

            throw new Exception("Branch term with {$propName} '{$term}' could not be found");
        }
    }
}
