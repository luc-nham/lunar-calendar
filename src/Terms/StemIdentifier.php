<?php

namespace LucNham\LunarCalendar\Terms;

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
}
