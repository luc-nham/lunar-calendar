<?php declare(strict_types=1);

namespace LunarCalendar;

use LunarCalendar\Converter\LunarDateTimeToSexagenaries;
use LunarCalendar\Formatter\HasTermInterface;
use LunarCalendar\Formatter\TermInterface;

class LunarSexagenary extends LunarDateTime implements HasTermInterface
{
    // Five characters to get Heavenly stems
    public const HEAVENLY_STEM_OF_DAY                  = 'D';
    public const HEAVENLY_STEM_OF_MONTH                = 'M';
    public const HEAVENLY_STEM_OF_YEAR                 = 'Y';
    public const HEAVENLY_STEM_OF_HOUR                 = 'H';
    public const HEAVENLY_STEM_OF_HOUR_BEGIN_NEW_DAY   = 'N';

    // Four characters to get Earthly branches
    public const EARTHLY_BRANCH_OF_DAY                 = 'd';
    public const EARTHLY_BRANCH_OF_MONTH               = 'm';
    public const EARTHLY_BRANCH_OF_YEAR                = 'y';
    public const EARTHLY_BRANCH_OF_HOUR                = 'h';

    // Base Vietnames format
    public const BASE_VIETNAMES  = 'Ngày {D} {d}, tháng {M} {m}, năm {Y} {y}, giờ {H} {h}';

    // To define option type format() method should be uses
    public const SEXAGENARY_FORMAT = 3;

    /**
     * Lunar Sexagenaries object
     *
     * @var LunarCalendar\Converter\LunarToSexagenarie
     */
    protected $sexagenaries;

    public function __construct(string $datetime = "now", ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
        $this->sexagenaries = new LunarDateTimeToSexagenaries($this->lunarDateTime);
    }

    /**
     * Return a term object
     *
     * @param string $key one character from: D, d, m, m, Y, y, H, h, N
     * @return TermInterface
     */
    public function getTerm(string $key): TermInterface
    {
        return $this->sexagenaries->getTerm($key);
    }

    /**
     * Return readability format from custom string
     *
     * @param string $format
     * @param string $formatType
     * @return string
     */
    public function format($format, int $formatType = self::SEXAGENARY_FORMAT): string
    {
        if($formatType != self::SEXAGENARY_FORMAT) {
            return parent::format($format, $formatType);
        }

        $formatKeys = [
            self::HEAVENLY_STEM_OF_DAY,
            self::HEAVENLY_STEM_OF_MONTH,
            self::HEAVENLY_STEM_OF_YEAR,
            self::HEAVENLY_STEM_OF_HOUR,
            self::HEAVENLY_STEM_OF_HOUR_BEGIN_NEW_DAY,

            self::EARTHLY_BRANCH_OF_DAY,
            self::EARTHLY_BRANCH_OF_MONTH,
            self::EARTHLY_BRANCH_OF_YEAR,
            self::EARTHLY_BRANCH_OF_HOUR
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