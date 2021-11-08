<?php declare(strict_types=1);

namespace LunarCalendar;

use LunarCalendar\Converter\GregorianToLunarDateTime;
use LunarCalendar\Formatter\LunarDateTimeStorageInterface;

class LunarDateTime extends \DateTime
{
    public const GREGORIAN_FORMAT   = 1;
    public const LUNAR_FORMAT       = 2;

    #[LunarDateTimeStorageInterface]
    protected $lunar_date;

    public function __construct(string $datetime = "now", ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
        $this->lunar_date = $this->getLunarDateTimeStorage();
    }

    /**
     * Create converter and store Lunar datetime
     *
     * @return void
     */
    private function getLunarDateTimeStorage(): LunarDateTimeStorageInterface
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

        return $lunarDateTime->output();
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
                $output = $this->addLeadingZero($this->lunar_date->get('d'));
                break;
            case 'j':
                $output = (string)$this->lunar_date->get('d');
                break;
            case 'J':
                $output = (string)$this->lunar_date->get('j'); 
                break;
            case 'm':
                $output = $this->addLeadingZero($this->lunar_date->get('m'));
                break;
            case 'n':
                $output = (string)$this->lunar_date->get('m');
                break;
            case 'l':
                $output = (string)$this->lunar_date->get('l');
                break;
            case 'L':
                $output = ($this->isLeapYear())? '1' : '0';
                break;
            case 'Y':
                $output = (string)$this->lunar_date->get('Y');
                break;
            case 'y':
                $output = substr((string)$this->lunar_date->get('Y'), -2);
                break; 
            default:
                throw new \Exception("Invalid Lunar date time format key '$key'.");
        }

        return $output;
    }

    /**
     * Check if is Lunar leap year
     *
     * @return boolean
     */
    public function isLeapYear(): bool
    {
        if(1 == $this->lunar_date->get('l')) {
            return true;
        }

        if(!$this->lunar_date->has('L')) {
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

                if(1 == $lunarDateTime->get('l') && $lunarDateTime->get('Y') == $this->lunar_date->get('Y')) {
                    $leapYear = 1;
                    break;
                }

                $m += 1;
            }

            $this->lunar_date->set('L', $leapYear);
        }

        return (1 == $this->lunar_date->get('L'))
                    ? true
                    : false;
    }

    /**
     * Return instance of LunarDateTimeStorageInterface
     *
     * @return LunarDateTimeStorageInterface
     */
    public function getLunarStorage(): LunarDateTimeStorageInterface
    {
        return $this->lunar_date;
    }
}