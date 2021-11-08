<?php declare(strict_types=1);

namespace LunarCalendar;

use LunarCalendar\Converter\GregorianToLunarDateTime;
use LunarCalendar\Formatter\LunarDateTimeStorage;

class LunarDateTime extends \DateTime
{
    public const GREGORIAN_FORMAT   = 1;
    public const LUNAR_FORMAT       = 2;

    /**
     * Store lunar date time
     *
     * @var LunarCalendar\Formatter\LunarDateTimeStorage;
     */
    protected $lunarDateTime;

    public function __construct(string $datetime = "now", ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);

        $this->lunarDateTime = new LunarDateTimeStorage();
        $this->setLunarDateTimeStorage();
    }

    /**
     * Create converter and store Lunar datetime
     *
     * @return void
     */
    private function setLunarDateTimeStorage(): void
    {
        $lunarDateTime = new GregorianToLunarDateTime(
            (int)parent::format('d'),
            (int)parent::format('m'),
            (int)parent::format('Y'),
            (int)parent::format('H'),
            (int)parent::format('i'),
            (int)parent::format('s'),
            (float)($this->getOffset() / 3600)
        );

        $this->lunarDateTime = $lunarDateTime->output();
        $this->setLeapYearOffset();
    }
    
    /**
     * Get Lunar date time format
     *
     * @param string $format
     * @return string
     */
    public function format($format, int $formatType = self::LUNAR_FORMAT): string
    {
        if($formatType == self::GREGORIAN_FORMAT) {
            return parent::format($format);
        }

        $formatLengh = strlen($format);
        
        if(1 == $formatLengh) {
            return $this->getLunarDateTime($format);
        }

        $lunarKeys = ['d', 'j', 'J', 'm', 'n', 'l', 'L', 'Y', 'y'];
        
        foreach($lunarKeys as $key) {
            while(str_contains($format, $key)) {
                $format = str_replace($key, $this->getLunarDateTime($key), $format);
            }
        }

        return $format;
    }

    /**
     * Get Gregorian date time
     *
     * @param string $format
     * @return string
     */
    public function gregorianFormat(string $format): string
    {
        return parent::format($format);
    }

    private function addLeadingZero(string|int $needle): string
    {
        if($needle < 10) {
            $needle = '0' . $needle;
        }

        return (string)$needle;
    }
    
    /**
     * Get single Lunar date time value
     *
     * @param string $key
     * @return string
     */
    private function getLunarDateTime(string $key): string
    {
        switch($key) {
            case 'd':
                $output = $this->addLeadingZero($this->lunarDateTime->get('d'));
                break;
            case 'j':
                $output = (string)$this->lunarDateTime->getDay();
                break;
            case 'J':
                $output = (string)$this->lunarDateTime->getJulianDayCount(); 
                break;
            case 'm':
                $output = $this->addLeadingZero($this->lunarDateTime->getMonth());
                break;
            case 'n':
                $output = (string)$this->lunarDateTime->getMonth();
                break;
            case 'l':
                $output = (string)$this->lunarDateTime->getLeapMonthOffset();
                break;
            case 'L':
                $output = (string)$this->lunarDateTime->GetLeapYearOffset();
                break;
            case 'Y':
                $output = (string)$this->lunarDateTime->getYear();
                break;
            case 'y':
                $output = substr((string)$this->lunarDateTime->getYear(), -2);
                break; 
            default:
                throw new \Exception("Invalid Lunar date time format key '$key'.");
        }

        return $output;
    }

    /**
     * Set Leap year offset
     *
     * @return boolean
     */
    private function setLeapYearOffset(): void
    {
        if(1 == $this->lunarDateTime->getLeapMonthOffset()) {
            $this->lunarDateTime->setLeapYearOffset(1);
            return;
        }

        // Start calculate
        $d        = 15;
        $m        = 1;
        $Y        = (int)parent::format('Y');
        $timezone = $this->getOffset() / 3600;
        $leapYear = 0;

        for($i = 0; $i < 15; ++$i) {
            if($m > 12) {
                $Y++;
                $m = 1;
            }

            $lunarDateTime = (new GregorianToLunarDateTime($d, $m, $Y, 0, 0, 0, $timezone))->output();

            if(1 == $lunarDateTime->get('l') && $lunarDateTime->get('Y') == $this->lunarDateTime->get('Y')) {
                $leapYear = 1;
                break;
            }

            $m += 1;
        }

        $this->lunarDateTime->setLeapYearOffset($leapYear);
    }

    /**
     * Return instance of LunarDateTimeStorage
     *
     * @return LunarDateTimeStorage
     */
    public function getLunarStorage(): LunarDateTimeStorage
    {
        return $this->lunarDateTime;
    }
}