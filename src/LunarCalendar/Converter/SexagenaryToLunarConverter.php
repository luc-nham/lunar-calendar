<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\LunarDateTimeStorageFormatter;
use LunarCalendar\Formatter\LunarEarthlyBranchFormatter as EarthlyBranch;
use LunarCalendar\Formatter\LunarHeavenlyStemFormatter as HeavenlyStem;
use LunarCalendar\LunarDateTime;

class SexagenaryToLunarConverter
{
    const MIN_LUNAR_YEAR = 1000;
    const MAX_LUNAR_YEAR = 9999;

    // Input
    protected $h_day;
    protected $e_day;
    protected $e_month;
    protected $e_year;
    protected $e_hour;

    /**
     * Lunar year to begin matching
     *
     * @var integer
     */
    protected $lunarYearBegin;

    /**
     * Set true to matching to Past, false in matching to Future
     *
     * @var boolean
     */
    protected $matchingToPast = true;

    /**
     * Store resuilts of matching
     *
     * @var array
     */
    protected $result = [];

    public function __construct(HeavenlyStem $h_day, EarthlyBranch $e_day, EarthlyBranch $e_month, EarthlyBranch $e_year, ?EarthlyBranch $e_hour = null) 
    {
        $this->h_day    = $h_day;
        $this->e_day    = $e_day;
        $this->e_month  = $e_month;
        $this->e_year   = $e_year;
        $this->e_hour   = $e_hour;

        if(null == $this->e_hour) {
            $this->e_hour = EarthlyBranch::createFromKey('ty');
        }

        if(!$this->validateTermsOfDay()) {
            throw new \Exception("Error. Both Heavenly stem and Earthly branch of Day must be same Yin or Yang nature.");
        }

        // Set Lunar year begin matching

    }

    public static function createFromTermKeys(string $h_day, string $e_day, string $e_month, string $e_year, ?string $e_hour = null): self
    {
        if(!$e_hour) {
            $e_hour = 'ty';
        }

        return new self(
            HeavenlyStem::createFromKey($h_day),
            EarthlyBranch::createFromKey($e_day),
            EarthlyBranch::createFromKey($e_month),
            EarthlyBranch::createFromKey($e_year),
            EarthlyBranch::createFromKey($e_hour),
        );
    }

    /**
     * Check if Both Heavenly Stem and Earthly Stem of Day can combine together
     *
     * @return boolean
     */
    private function validateTermsOfDay(): bool
    {
        $hDayType = $this->h_day->getOffset() % 2;
        $eDayType = $this->e_day->getOffset() % 2;

        return ($hDayType != $eDayType)
            ? false
            : true;
    }

    /**
     * Get default Lunar year to begin matching
     *
     * @return integer
     */
    private function getDefaultLunarYearNumber(): int
    {
        if(!$this->lunarYearBegin) {
            $lunar = new LunarDateTime();
            $this->lunarYearBegin = (int)$lunar->format('Y');
        }

        return $this->lunarYearBegin;
    }

    /**
     * Convert a Lunar year numer to Earthly Branch Of Year
     *
     * @param integer|string $yearNumber
     * @return EarthlyBranch
     */
    private function getEarthlyBranchOfYear(int|string $yearNumber): EarthlyBranch
    {
        $lunarStorage = new LunarDateTimeStorageFormatter();
        $lunarStorage->set('Y', $yearNumber);

        $term  = new LunarYearEarthlyBranchConverter($lunarStorage);
        return $term->getTerm();
    }

    /**
     * Get Lunar year number to begin matching with Earthly Stem Of Year
     *
     * @return integer
     */
    public function getLunarYearBeginMatching(): int
    {
        $startYearNumber = $this->getDefaultLunarYearNumber();
        $compareOffset   = $this->getEarthlyBranchOfYear($startYearNumber)->getOffset();

        while($compareOffset != $this->e_year->getOffset()) {
            $startYearNumber = ($this->matchingToPast)
                ? $startYearNumber - 1
                : $startYearNumber + 1;

            $compareOffset   = $this->getEarthlyBranchOfYear($startYearNumber)->getOffset();
        }

        return $startYearNumber;
    }
    
    public function matching()
    {
        // Prepare to matching
        $ouput = new LunarDateTimeStorageFormatter();
        $ouput->set('Y', $this->getLunarYearBeginMatching());
        $ouput->set('m', $this->e_month->getOffset() + 1); // Lunar Month offset to month number
        $ouput->set('d', 1);

    }
}