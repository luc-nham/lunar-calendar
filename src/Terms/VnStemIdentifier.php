<?php

namespace LucNham\LunarCalendar\Terms;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;

#[SexagenaryTermAttribute(key: 'giap', name: 'Giáp', position: 0)]
#[SexagenaryTermAttribute(key: 'at', name: 'Ất', position: 1)]
#[SexagenaryTermAttribute(key: 'binh', name: 'Bính', position: 2)]
#[SexagenaryTermAttribute(key: 'dinh', name: 'Đinh', position: 3)]
#[SexagenaryTermAttribute(key: 'mau', name: 'Mậu', position: 4)]
#[SexagenaryTermAttribute(key: 'ky', name: 'Kỷ', position: 5)]
#[SexagenaryTermAttribute(key: 'canh', name: 'Canh', position: 6)]
#[SexagenaryTermAttribute(key: 'tan', name: 'Tân', position: 7)]
#[SexagenaryTermAttribute(key: 'nham', name: 'Nhâm', position: 8)]
#[SexagenaryTermAttribute(key: 'quy', name: 'Quý', position: 9)]
readonly class VnStemIdentifier extends StemIdentifier {}
