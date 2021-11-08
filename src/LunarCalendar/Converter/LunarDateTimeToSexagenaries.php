<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\HasTermInterface;
use LunarCalendar\Formatter\LunarDateTimeStorageInterface;
use LunarCalendar\Formatter\EarthlyBranchTerm as EarthlyBranch;
use LunarCalendar\Formatter\HeavenlyStemTerm as HeavenlyStem;
use LunarCalendar\Formatter\BaseTerm;

/**
 * Convert a LunarDateTimeStorageInterface to Sexageneries Term
 * 
 * @author Van Tran <caovan.info@gmail.com>
 */
class LunarDateTimeToSexagenaries implements HasTermInterface
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

    /**
     * Lunar storage input
     *
     * @var \LunarCalendar\Formatter\LunarDateTimeStorageInterface
     */
    protected $lunarDateTime;

    /**
     * Store caulated Sexagenaries term
     *
     * @var array
     */
    protected $cache = [];

    public function __construct(LunarDateTimeStorageInterface $lunarDateTime)
    {
        $this->lunarDateTime = $lunarDateTime;
    }

    /**
     * Check if cache key exists
     *
     * @param string $storedKey
     * @return boolean
     */
    protected function hasCache(string $storedKey): bool
    {
        return array_key_exists($storedKey, $this->cache)
            ? true
            :false;
    }

    /**
     * Store Term to cache
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function setCache(string $key, BaseTerm $value): void
    {
        $this->cache[$key] = $value;
    }

    /**
     * Receive Term
     *
     * @param string $key
     * @return BaseTerm
     */
    protected function getCache(string $key): BaseTerm
    {
        if(!$this->hasCache($key)) {
            throw new \Exception("Error. Term do not exists with key $key.");
        }

        return $this->cache[$key];
    }

    /**
     * Get Hevenly stem of Day
     *
     * @return HevenlyStem
     */
    public function getHeavenlyStemOfDay(): HeavenlyStem
    {
        if(! $this->hasCache(self::HEAVENLY_STEM_OF_DAY)) {
            $offset = (floor($this->lunarDateTime->getJulianDayCount()) + 9) % 10;
            $term   = new HeavenlyStem($offset);

            $this->setCache(self::HEAVENLY_STEM_OF_DAY, $term);
        }

        return $this->getCache(self::HEAVENLY_STEM_OF_DAY);
    }

    /**
     * Get Earthly Branch of Day
     *
     * @return EarthlyBranch
     */
    public function getEarthlyStemOfDay(): EarthlyBranch
    {
        if(!$this->hasCache(self::EARTHLY_BRANCH_OF_DAY)) {
            $offset = (floor($this->lunarDateTime->getJulianDayCount()) + 1) % 12;

            $this->setCache(
                self::EARTHLY_BRANCH_OF_DAY,
                new EarthlyBranch($offset)
            );
        }

        return $this->getCache(self::EARTHLY_BRANCH_OF_DAY);
    }

    /**
     * Get Heavenly Stem Of Hour which begin new Lunar day
     *
     * @return HeavenlyStem
     */
    public function getHeavenlyStemOfHourBeginNewDay(): HeavenlyStem
    {
        if(!$this->hasCache(self::HEAVENLY_STEM_OF_HOUR_BEGIN_NEW_DAY)) {
            $offset             = 0;
            $compare            = 0;
            $heavenlyStemOfDay  = $this->getHeavenlyStemOfDay();

            while($compare != $heavenlyStemOfDay->getOffset()) {
                $compare    = ($compare + 1) % 10;
                $offset     = ($offset + 2) % 10;      
            }

            $this->setCache(
                self::HEAVENLY_STEM_OF_HOUR_BEGIN_NEW_DAY,
                new HeavenlyStem($offset)
            );
        }

        return $this->getCache(self::HEAVENLY_STEM_OF_HOUR_BEGIN_NEW_DAY);
    }

    /**
     * Get Heavenly Stem Of Current Hours
     *
     * @return HeavenlyStem
     */
    public function getHeavenlyStemOfHour(): HeavenlyStem
    {
        if(!$this->hasCache(self::HEAVENLY_STEM_OF_HOUR)) {
            $offset     = $this->getHeavenlyStemOfHourBeginNewDay()->getOffset();
            $compareH   = 23;

            while($compareH != $this->lunarDateTime->getHours()) {
                $compareH = ($compareH + 1) % 24;
                
                if($compareH % 2 != 0) {
                    $offset = ($offset + 1) % 10;
                }
            }

            $this->setCache(
                self::HEAVENLY_STEM_OF_HOUR,
                new HeavenlyStem($offset)
            );
        }

        return $this->getCache(self::HEAVENLY_STEM_OF_HOUR);
    }

    /**
     * Get Earthly Stem Of Current Hour
     *
     * @return EarthlyBranch
     */
    public function getEarthlyBranchOfHour(): EarthlyBranch
    {
        if(!$this->hasCache(self::EARTHLY_BRANCH_OF_HOUR)) {
            $compareH   = 23;   // Lunar new day start at 23:00 
            $offset     = 0;    // 0 is Rat/Tý/Zǐ...

            while($compareH != $this->lunarDateTime->getHours()) {
                $compareH = ($compareH + 1) % 24;
                
                if($compareH % 2 != 0) {
                    $offset = ($offset + 1) % 12;
                }
            }

            $this->setCache(
                self::EARTHLY_BRANCH_OF_HOUR,
                new EarthlyBranch($offset)
            );
        }

        return $this->getCache(self::EARTHLY_BRANCH_OF_HOUR);
    }

    /**
     * Get Heavenly Stem Of Month
     *
     * @return HeavenlyStem
     */
    public function getHeavenlyStemOfMonth(): HeavenlyStem
    {
        if(!$this->hasCache(self::HEAVENLY_STEM_OF_MONTH)) {
            $offset = ($this->lunarDateTime->getYear() * 12 + $this->lunarDateTime->getMonth() + 3) % 10;

            $this->setCache(
                self::HEAVENLY_STEM_OF_MONTH,
                new HeavenlyStem($offset)
            );
        }

        return $this->getCache(self::HEAVENLY_STEM_OF_MONTH);
    }

    /**
     * Get Earthly Branch Of Month
     *
     * @return EarthlyBranch
     */
    public function getEarthlyBranchOfMonth(): EarthlyBranch
    {
        if(!$this->hasCache(self::EARTHLY_BRANCH_OF_MONTH)) {
            $offset = ($this->lunarDateTime->getMonth() + 1) % 12;

            $this->setCache(
                self::EARTHLY_BRANCH_OF_MONTH,
                new EarthlyBranch($offset)
            );
        }

        return $this->getCache(self::EARTHLY_BRANCH_OF_MONTH);
    }

    /**
     * Get Heavenly Stem Of Year
     *
     * @return HeavenlyStem
     */
    public function getHeavenlyStemOfYear(): HeavenlyStem
    {
        if(!$this->hasCache(self::HEAVENLY_STEM_OF_YEAR)) {
            $offset = ($this->lunarDateTime->getYear() + 6) % 10;

            $this->setCache(
                self::HEAVENLY_STEM_OF_YEAR,
                new HeavenlyStem($offset)
            );
        }

        return $this->getCache(self::HEAVENLY_STEM_OF_YEAR);
    }

    /**
     * Get Earthly Stem Of Year
     *
     * @return EarthlyBranch
     */
    public function getEarthlyBranchOfYear(): EarthlyBranch
    {
        if(!$this->hasCache(self::EARTHLY_BRANCH_OF_YEAR)) {
            $offset = ($this->lunarDateTime->getYear() + 8) % 12;

            $this->setCache(
                self::EARTHLY_BRANCH_OF_YEAR,
                new EarthlyBranch($offset)
            );
        }

        return $this->getCache(self::EARTHLY_BRANCH_OF_YEAR);
    }

    public function getTerm(string $key): BaseTerm
    {
        switch($key) {

            case self::HEAVENLY_STEM_OF_HOUR_BEGIN_NEW_DAY:
                $term = $this->getHeavenlyStemOfHourBeginNewDay();
                break;

            case self::HEAVENLY_STEM_OF_HOUR:
                $term = $this->getHeavenlyStemOfHour();
                break;

            case self::HEAVENLY_STEM_OF_DAY:
                $term = $this->getHeavenlyStemOfDay();
                break;

            case self::HEAVENLY_STEM_OF_MONTH:
                $term = $this->getHeavenlyStemOfMonth();
                break;

            case self::HEAVENLY_STEM_OF_YEAR:
                $term = $this->getHeavenlyStemOfYear();
                break;

            case self::EARTHLY_BRANCH_OF_HOUR:
                $term = $this->getEarthlyBranchOfHour();
                break;

            case self::EARTHLY_BRANCH_OF_DAY:
                $term = $this->getEarthlyStemOfDay();
                break;

            case self::EARTHLY_BRANCH_OF_MONTH:
                $term = $this->getEarthlyBranchOfMonth();
                break;

            case self::EARTHLY_BRANCH_OF_YEAR:
                $term = $this->getEarthlyBranchOfYear();
                break;

            default:
                throw new \Exception("Error. Term with key '$key' does not exists.");
        }

        return $term;
    }

}