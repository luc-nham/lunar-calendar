<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\LunarDateTimeFormatter;
use LunarCalendar\Formatter\SexagenaryTermFormatter;

class LunarSexagenaryConverter
{
    public const HEAVENLY_STEM_DAY                  = 'D';
    public const HEAVENLY_STEM_MONTH                = 'M';
    public const HEAVENLY_STEM_YEAR                 = 'Y';
    public const HEAVENLY_STEM_HOUR                 = 'H';
    public const HEAVENLY_STEM_HOUR_BEGIN_NEW_DAY   = 'N';

    public const EARTHLY_BRANCH_DAY                 = 'd';
    public const EARTHLY_BRANCH_MONTH               = 'm';
    public const EARTHLY_BRANCH_YEAR                = 'y';
    public const EARTHLY_BRANCH_HOUR                = 'h';

    protected $lunarDateTime;

    public function __construct(LunarDateTimeFormatter $lunarDateTime)
    {
        $this->lunarDateTime = $lunarDateTime;
    }

    public function getTerm(string $key): SexagenaryTermFormatter
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

        $converter = new $class($this->lunarDateTime);
        return $converter->getTerm();
    }
}