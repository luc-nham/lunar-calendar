<?php

namespace LucNham\LunarCalendar\Terms;

use Exception;
use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;

#[SexagenaryTermAttribute(key: 'giap', name: 'Giap', position: 0)]
#[SexagenaryTermAttribute(key: 'at', name: 'At', position: 1)]
#[SexagenaryTermAttribute(key: 'binh', name: 'Binh', position: 2)]
#[SexagenaryTermAttribute(key: 'dinh', name: 'Dinh', position: 3)]
#[SexagenaryTermAttribute(key: 'mau', name: 'Mau', position: 4)]
#[SexagenaryTermAttribute(key: 'ky', name: 'Ky', position: 5)]
#[SexagenaryTermAttribute(key: 'canh', name: 'Canh', position: 6)]
#[SexagenaryTermAttribute(key: 'tan', name: 'Tan', position: 7)]
#[SexagenaryTermAttribute(key: 'nham', name: 'Nham', position: 8)]
#[SexagenaryTermAttribute(key: 'quy', name: 'Quy', position: 9)]
readonly class StemIdentifier extends SexagenaryIdentifier
{
    /**
     * @inheritDoc
     */
    protected function registerType(): string
    {
        return 'S';
    }

    /**
     * Returns the Stem term identification
     * 
     * @param string|int $term The term key, name or position
     * @param string $target Target class name
     * @return StemIdentifier
     */
    public static function resolve(
        string|int $term,
        string $target = StemIdentifier::class,
    ): StemIdentifier {
        try {
            return parent::resolve($term, $target);
        } catch (\Throwable $th) {
            $propName = is_string($term) ? 'name or key' : 'position';

            throw new Exception("Stem term with {$propName} '{$term}' could not be found");
        }
    }
}
