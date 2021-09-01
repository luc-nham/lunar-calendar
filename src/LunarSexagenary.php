<?php declare(strict_types=1);

namespace LunarCalendar;

use LunarCalendar\Converter\LunarSexagenaryConverter;
use LunarCalendar\Formatter\ReadabilityInterface;

class LunarSexagenary extends LunarSexagenaryConverter implements ReadabilityInterface
{
    public const BASE_FORMAT            = 'D: {D} {d}, M: {M} {m}, Y: {Y} {y}, H: {H} {h}';
    public const BASE_VIETNAMES_FORMAT  = 'Ngày {D} {d}, tháng {M} {m}, năm {Y} {y}, giờ {H} {h}';

    public function format(string $format): string
    {
        $formatKeys = [
            self::HEAVENLY_STEM_DAY,
            self::HEAVENLY_STEM_MONTH,
            self::HEAVENLY_STEM_YEAR,
            self::HEAVENLY_STEM_HOUR,
            self::HEAVENLY_STEM_HOUR_BEGIN_NEW_DAY,

            self::EARTHLY_BRANCH_DAY,
            self::EARTHLY_BRANCH_MONTH,
            self::EARTHLY_BRANCH_YEAR,
            self::EARTHLY_BRANCH_HOUR
        ];

        $inputFormatKeys = [];
        preg_match_all('#\{(.*?)\}#', $format, $inputFormatKeys);

        if(count($inputFormatKeys) > 0) {
            foreach($inputFormatKeys[1] as $offset => $key) {
                if(in_array($key, $formatKeys)){
                    $format = str_replace(
                        $inputFormatKeys[0][$offset], 
                        $this->getTerm($key)->getLabel(),
                        $format
                    );
                }
            }
        }

        return $format;
    }
}
