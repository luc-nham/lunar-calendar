<?php declare(strict_types=1);

namespace LunarCalendar;

use LunarCalendar\Converter\LunarDayEarthlyBranchConverter;
use LunarCalendar\Converter\LunarDayHeavenlyStemConverter;
use LunarCalendar\Converter\LunarHourBeginNewDayHeavenlyStemConverter;
use LunarCalendar\Converter\LunarHourEarthlyBranchConverter;
use LunarCalendar\Converter\LunarHourHeavenlyStemConverter;
use LunarCalendar\Converter\LunarMonthHeavenlyStemConverter;
use LunarCalendar\Converter\LunarMonthEarthlyBranchConverter;
use LunarCalendar\Converter\LunarYearHeavenlyStemConverter;
use LunarCalendar\Converter\LunarYearEarthlyBranchConverter;
use LunarCalendar\Formatter\TermInterface;

class LunarSexagenary extends LunarDateTime
{
    // Five characters to get Heavenly stems
    public const HEAVENLY_STEM_DAY                  = 'D';
    public const HEAVENLY_STEM_MONTH                = 'M';
    public const HEAVENLY_STEM_YEAR                 = 'Y';
    public const HEAVENLY_STEM_HOUR                 = 'H';
    public const HEAVENLY_STEM_HOUR_BEGIN_NEW_DAY   = 'N';

    // Four characters to get Earthly branches
    public const EARTHLY_BRANCH_DAY                 = 'd';
    public const EARTHLY_BRANCH_MONTH               = 'm';
    public const EARTHLY_BRANCH_YEAR                = 'y';
    public const EARTHLY_BRANCH_HOUR                = 'h';

    // Base Vietnames format
    public const BASE_VIETNAMES  = 'Ngày {D} {d}, tháng {M} {m}, năm {Y} {y}, giờ {H} {h}';

    // To define option type format() method should be uses
    public const SEXAGENARY_FORMAT = 3;

    /**
     * Return a term object
     *
     * @param string $key one character from: D, d, m, m, Y, y, H, h, N
     * @return TermInterface
     */
    public function getTerm(string $key): TermInterface
    {
        switch($key) {
            case self::HEAVENLY_STEM_DAY:
                $class = LunarDayHeavenlyStemConverter::class;
                break;
            case self::HEAVENLY_STEM_MONTH:
                $class = LunarMonthHeavenlyStemConverter::class;
                break;
            case self::HEAVENLY_STEM_YEAR:
                $class = LunarYearHeavenlyStemConverter::class;
                break;
            case self::HEAVENLY_STEM_HOUR:
                $class = LunarHourHeavenlyStemConverter::class;
                break;
            case self::HEAVENLY_STEM_HOUR_BEGIN_NEW_DAY:
                $class = LunarHourBeginNewDayHeavenlyStemConverter::class;
                break;
            case self::EARTHLY_BRANCH_DAY:
                $class = LunarDayEarthlyBranchConverter::class;
                break;
            case self::EARTHLY_BRANCH_MONTH:
                $class = LunarMonthEarthlyBranchConverter::class;
                break;
            case self::EARTHLY_BRANCH_YEAR:
                $class = LunarYearEarthlyBranchConverter::class;
                break;
            case self::EARTHLY_BRANCH_HOUR:
                $class = LunarHourEarthlyBranchConverter::class;
                break;
            default:
                throw new \Exception("Invalid key."); 
        }

        $converter = new $class($this->lunar_date);
        return $converter->getTerm();
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