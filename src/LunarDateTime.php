<?php namespace VanTran\LunarCalendar;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Throwable;
use VanTran\LunarCalendar\Lunar\GregorianToLunarCorrector;
use VanTran\LunarCalendar\Lunar\LunarBaseComponentInterface;
use VanTran\LunarCalendar\Lunar\LunarDateTimeCorrector;
use VanTran\LunarCalendar\Lunar\LunarDateTimeFormatter;
use VanTran\LunarCalendar\Lunar\LunarDateTimeInput;
use VanTran\LunarCalendar\Lunar\LunarParser;

/**
 * Ngày tháng Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar
 */
class LunarDateTime implements LunarDateTimeInteface
{
    /**
     * 
     * @var LunarBaseComponentInterface Các thành phần chính cấu tạo Âm lịch
     */
    private $component;

    /**
     * @var LunarDateTimeFormatter Đối tượng hỗ trợ định dạng thời gian đầu ra
     */
    private $formatter;

    /**
     * @var null|DateTime Thời gian dương lịch tương ứng, có sẵn khi lấy thời điểm 'hiện tại'
     */
    protected $gregorian;

    /**
     * Tạo đối tượng mới
     * 
     * @param string $datetime Chuỗi thòi gian âm lịch, để trống hoặc đặt 'now' để lấy thời điểm hiện tại
     * @param null|DateTimeZone $timezone Múi giờ địa phương. Nếu không cung cấp mặc định sẽ sử dụng '+07:00'
     * @return void 
     * @throws Exception 
     * @throws Throwable 
     */
    public function __construct(private $datetime = 'now', private ?DateTimeZone $timezone = null)
    {
        $this->init();
    }

    /**
     * Khởi tạo nhanh ngày tháng Âm lịch
     * 
     * @param null|DateTimeZone $timezone Múi giờ địa phương. Nếu không cung cấp mặc định sẽ sử dụng '+07:00'
     * @return LunarDateTime 
     */
    public static function now(?DateTimeZone $timezone = null): LunarDateTime
    {
        if (!$timezone) {
            $timezone = new DateTimeZone(self::VN_TIMEZONE);
        }

        return new self('now', $timezone);
    }

    /**
     * Khởi tạo dữ liệu
     * 
     * @return void 
     * @throws Exception 
     * @throws Throwable 
     */
    protected function init(): void
    {
        $this->initComponent();
        $this->initFormatter();
    }

    /**
     * Khởi tạo các thành phần cấu tạo âm lịch
     * @return void 
     * @throws Exception 
     * @throws Throwable 
     */
    protected function initComponent(): void
    {
        $datetime = $this->datetime;

        if ($datetime === 'now' || $datetime === '') {
            $date = new DateTime('now', $this->getTimezone());
            $input = (new LunarDateTimeInput())
                        ->setYear($date->format('Y'))
                        ->setMonth($date->format('n'))
                        ->setDay($date->format('j'))
                        ->setHour($date->format('H'))
                        ->seMinute($date->format('i'))
                        ->setSecond($date->format('s'))
                        ->setOffset($date->getOffset())
                        ->setTimeZone($date->getTimezone());
            
            $this->gregorian = $date;
            $this->component = new GregorianToLunarCorrector($input);
        }
        else {
            $paser = new LunarParser($datetime, $this->getTimezone());

            if ($paser->hasError()) {
                throw new Exception("Parse error. Lunar date time invalid.");
            }

            $this->component = new LunarDateTimeCorrector($paser);
        }
    }

    /**
     * Khởi tạo bộ định dạng thời gian đầu ra
     * @return void 
     */
    protected function initFormatter(): void
    {
        $this->formatter = new LunarDateTimeFormatter($this->component);
    }

    /**
     * {@inheritdoc}
     */
    public function format(string $format): string 
    { 
        return $this->formatter->format($format);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimezone(): DateTimeZone 
    { 
        if (!$this->timezone) {
            $this->timezone = new DateTimeZone(self::VN_TIMEZONE);
        }

        return $this->timezone;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int 
    { 
        return $this->component->getOffset();
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp(): int 
    { 
        return (int)$this->format('U');
    }

    /**
     * {@inheritdoc}
     */
    public function toDateTime(): DateTime
    {
        if (!$this->gregorian) {
            $date = new DateTime('now', $this->getTimezone());
            $date->setTimestamp($this->format('U'));

            $this->gregorian = $date;
        }

        return $this->gregorian;
    }

    /**
     * {@inheritdoc}
     */
    public function getJd(): float 
    { 
        return $this->component->getJd();
    }

    /**
     * {@inheritdoc}
     */
    public function getMidnightJd(): float 
    { 
        return $this->component->getMidnightJd();
    }

    /**
     * {@inheritdoc}
     */
    public function getDiffJd(): float 
    { 
        return $this->component->getDiffJd();
    }
}