<?php declare(strict_types=1);

namespace LunarCalendar;

use LunarCalendar\Converter\LunarDateTimeConverter;
use LunarCalendar\Formatter\ReadabilityInterface;

class LunarDateTime extends LunarDateTimeConverter implements ReadabilityInterface
{
    public const BASE_DASHED_FORMAT      = 'd-m-Y';
    public const BASE_SLASHED_FORMAT     = 'd/m/Y';
    public const BASE_VIETNAMES_FORMAT   = 'Ngày {d} tháng {m} năm {Y}';
    
    public function format(string $format): string
    {
        $allowKeys       = ['d', 'm', 'y', 'D', 'M', 'Y'];
        $inputFormatKeys = [];
        preg_match_all('#\{(.*?)\}#', $format, $inputFormatKeys);

        if(count($inputFormatKeys[0]) > 0) {
            foreach($inputFormatKeys[1] as $offset => $key) {
                if(in_array($key, $allowKeys)){
                    $format = str_replace(
                        $inputFormatKeys[0][$offset], 
                        (string)$this->datetime()->getDate($key),
                        $format
                    );
                }
            }
        }
        else {
            foreach($allowKeys as $key) {
                while(str_contains($format, $key)) {
                    $format = str_replace(
                        $key,
                        (string)$this->datetime()->getDate($key),
                        $format
                    );
                }
            }
        }

        return $format;
    }
}