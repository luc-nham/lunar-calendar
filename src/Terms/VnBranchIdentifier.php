<?php

namespace LucNham\LunarCalendar\Terms;

use LucNham\LunarCalendar\Attributes\SexagenaryTermAttribute;

#[SexagenaryTermAttribute(key: 'ty', name: 'Tý', position: 0)]
#[SexagenaryTermAttribute(key: 'suu', name: 'Sửu', position: 1)]
#[SexagenaryTermAttribute(key: 'dan', name: 'Dần', position: 2)]
#[SexagenaryTermAttribute(key: 'mao', name: 'Mão', position: 3)]
#[SexagenaryTermAttribute(key: 'thin', name: 'Thìn', position: 4)]
#[SexagenaryTermAttribute(key: 'ti', name: 'Tị', position: 5)]
#[SexagenaryTermAttribute(key: 'ngo', name: 'Ngọ', position: 6)]
#[SexagenaryTermAttribute(key: 'mui', name: 'Mùi', position: 7)]
#[SexagenaryTermAttribute(key: 'than', name: 'Thân', position: 8)]
#[SexagenaryTermAttribute(key: 'dau', name: 'Dậu', position: 9)]
#[SexagenaryTermAttribute(key: 'tuat', name: 'Tuất', position: 10)]
#[SexagenaryTermAttribute(key: 'hoi', name: 'Hợi', position: 11)]
readonly class VnBranchIdentifier extends BranchIdentifier {}
