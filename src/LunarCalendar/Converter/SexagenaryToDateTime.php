<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Converter\Traits\GregorianToJulian;
use LunarCalendar\Converter\Traits\JulianToGregorian;
use LunarCalendar\Formatter\LunarDateTimeStorage;
use LunarCalendar\Formatter\EarthlyBranchTerm as EarthlyBranch;
use LunarCalendar\Formatter\HeavenlyStemTerm as HeavenlyStem;
use LunarCalendar\Formatter\LunarDateTimeStorageInterface;
use LunarCalendar\LunarDateTime;

/**
 * Matching a Sexagenariy terms set to gregorian date time string
 * 
 * @author Van Tran <caovan.info@gmail.com>
 */
class SexagenaryToDateTime
{
    use GregorianToJulian;
    use JulianToGregorian;

    const MAX_YEAR = 1000;

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
     * Store matching result, true or false
     *
     * @var boolean
     */
    protected $isMatched = false;

    /**
     * Store ouput timestamp
     *
     * @var string
     */
    protected $ouputTimestamp;

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
    }

    /**
     * Set lunar year begin matching
     *
     * @param integer|string $year
     * @return self
     */
    public function setLunarYearBegin(int|string $year): self
    {
        if($year < 1000) {
            throw new \Exception("Error. Lunar year to begin matching must bigger than 1000.");
        }

        $this->lunarYearBegin = $year;
        return $this;
    }

    /**
     * Set Matching to Past
     *
     * @param boolean $status
     * @return self
     */
    public function setMatchingToPast(bool $status): self
    {
        $this->matchingToPast = $status;
        return $this;
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
        $lunarStorage = new LunarDateTimeStorage();
        $lunarStorage->setYear($yearNumber);

        $converter  = new LunarDateTimeToSexagenaries($lunarStorage);
        return $converter->getTerm($converter::EARTHLY_BRANCH_OF_YEAR);
    }

    /**
     * Get Lunar year number to begin matching with Earthly Stem Of Year
     *
     * @return integer
     */
    private function getLunarYearBeginMatching(): int
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

    /**
     * Convert Earthly Branch of Month input to Lunar month number
     *
     * @return integer
     */
    private function getInputLunarMonthNumber(): int
    {
        $monthNumber = $this->e_month->getOffset() - 1;

        if($monthNumber <= 0) {
            $monthNumber += 12;
        }

        return $monthNumber;
    }

    private function getInputHourNumber(): int
    {
        $lunarHourOffset            = 0;
        $equivalentGregorianHours   = 23;
        $loopCouter                 = 0;

        while($lunarHourOffset != $this->e_hour->getOffset()) {
            $lunarHourOffset            = ($lunarHourOffset + 1) % 12;
            $equivalentGregorianHours   = ($equivalentGregorianHours + 2) % 24;

            ++$loopCouter;

            if($loopCouter >= 12) {
                throw new \Exception('Eror. Can not convert Lunar hours to Gregorian hours. Check Earthly Branch Of Hour input.');
            }
        }

        return $equivalentGregorianHours;
    }
    
    /**
     * Prepare Lunar Date Time to Begin matching
     *
     * @return LunarDateTimeStorage
     */
    private function prepareLunarStorage(): LunarDateTimeStorage
    {
        $lunarDateTime = (new LunarDateTimeStorage())
            ->setYear($this->getLunarYearBeginMatching())
            ->setMonth($this->getInputLunarMonthNumber())
            ->setDay(1)
            ->setLeapMonthOffset(0)
            ->setTimeZone(0);

        return $lunarDateTime;
    }

    /**
     * Add Julian day count to lunar storage while matching in Main loop
     *
     * @param LunarDateTimeStorageInterface $lunarStorage
     * @return void
     */
    private function addJdWhileMatching(LunarDateTimeStorageInterface &$lunarStorage): void
    {
        $datetime = LunarDateTimeToGregorian::createFromLunarStorage($lunarStorage);
        $jd       = $this->dateTimeToJd($datetime->output());

        if($this->e_hour->getOffset() == 0) {
            $jd += 1;
        }

        $lunarStorage->setJulianDayCount($jd);
    }

    /**
     * Check if a pre-result of Julian day count is matched with Lunar month
     * input or not (inside Main loop)
     *
     * @param integer|float $julianDayCount
     * @return boolean
     */
    private function validJdMatched(int|float $julianDayCount): bool
    {
        $gregorian     = $this->jdToGregorian($julianDayCount);
        $lunarDatetime = (new GregorianToLunarDateTime(
            $gregorian['d'],
            $gregorian['m'],
            $gregorian['Y'],
            0,                  // We don't need to use Hour
            0,                  
            0,
            7                   // Timezone should be 7 (checked at Ho Chi Minh)
        ))->output();

        return ($lunarDatetime->getMonth() == $this->getInputLunarMonthNumber())
            ? true
            : false;
    }

    /**
     * Convert matched Julian Day Count to Unix Timestamp
     *
     * @param integer $resultJulianDayCount
     * @return integer
     */
    private function getOutputDateTimeString(int $resultJulianDayCount): string
    {
        $date = jdtogregorian($resultJulianDayCount);
        $time = $this->getInputHourNumber() . ':00:00';

        return $date . ' ' . $time;
    }
    
    /**
     * Main loop:
     *
     * @return integer
     */
    protected function mainLoop(): string
    {
        $lunarDateTime          = $this->prepareLunarStorage();
        $resultJulianDayCount   = 0;

        do {
            // Get Julian day count
            $this->addJdWhileMatching($lunarDateTime);

            // Check if in lunar month have matched Heavenly Stem and Earthly Branch of Day
            $counterDay = 0;

            do {
                $sexagenaries       = new LunarDateTimeToSexagenaries($lunarDateTime);
                $heavenlyStemOfDay  = $sexagenaries->getTerm($sexagenaries::HEAVENLY_STEM_OF_DAY);
                $earthlyBranchOfDay = $sexagenaries->getTerm($sexagenaries::EARTHLY_BRANCH_OF_DAY);

                if($heavenlyStemOfDay->getKey() == $this->h_day->getKey()) {
                    if($earthlyBranchOfDay->getKey() == $this->e_day->getKey()) {

                        $matchedJd = $lunarDateTime->getJulianDayCount();

                        if($this->validJdMatched($matchedJd)) {
                            $resultJulianDayCount = $matchedJd;

                            break;
                        }
                    }
                }

                $newJulianDayCount = $lunarDateTime->getJulianDayCount() + 1;
                $lunarDateTime->setJulianDayCount($newJulianDayCount);

                ++$counterDay;

            } while ($counterDay < 60);

            // Increase or decrease Lunar year numbers to repeat
            $newLunarYear = ($this->matchingToPast)
                ? $lunarDateTime->getYear() - 12
                : $lunarDateTime->getYear() + 12;

            // Break loop conditions
            if($this->lunarYearBegin - self::MAX_YEAR == 0) {
                break;
            }

            if(abs($newLunarYear - $this->lunarYearBegin) > self::MAX_YEAR ) {
                break;
            }

            // Set new lunar year and continue the loop
            $lunarDateTime->setYear($newLunarYear);

        } while ($resultJulianDayCount == 0);

        // Check if the loop found a result
        if($resultJulianDayCount > 0) {
            $this->isMatched = true;

            // Legalize Julian day output
            if($this->e_hour->getOffset() == 0) {
                $resultJulianDayCount -= 1;
            }
        }

        return (!$this->isMatched)
            ? '0'
            : $this->getOutputDateTimeString($resultJulianDayCount);
    }

    /**
     * Check if matched
     *
     * @return boolean
     */
    public function isMatched(): bool
    {
        return ('0' != $this->output())
            ? true
            : false;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function output(): string
    {
        if(!$this->ouputTimestamp) {
            $this->ouputTimestamp = $this->mainLoop();
        }

        return $this->ouputTimestamp;
    }
}