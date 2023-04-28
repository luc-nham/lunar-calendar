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
     * @var DateTimeZone Múi giờ địa phương mặc định +0700
     */
    private static $defaultTimeZone;

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
     * Khởi tạo đối tượng từ 1 đối tượng DateTime
     * 
     * @param DateTime $datetime 
     * @return LunarDateTime 
     * @throws Exception 
     */
    public static function createFromDateTime(DateTimeInterface $datetime): self
    {
        $input = new LunarDateTimeInput();
        $input->setYear($datetime->format('Y'))
                ->setMonth($datetime->format('n'))
                ->setDay($datetime->format('j'))
                ->setHour($datetime->format('H'))
                ->seMinute($datetime->format('i'))
                ->setSecond($datetime->format('s'))
                ->setOffset($datetime->getOffset())
                ->setTimeZone($datetime->getTimezone());
        
        $component = new GregorianToLunarCorrector($input);

        $ins = new self('', $datetime->getTimezone());
        $ins->setComponent($component);

        return $ins;
    }

    protected static function getDefaultTimeZone(): DateTimeZone
    {
        if (!self::$defaultTimeZone) {
            self::$defaultTimeZone = new DateTimeZone(self::VN_TIMEZONE);
        }

        return self::$defaultTimeZone;
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
            $component = new GregorianToLunarCorrector($input);
        }
        else {
            $paser = new LunarParser($datetime, $this->getTimezone());

            if ($paser->hasError()) {
                throw new Exception("Parse error. Lunar date time invalid.");
            }

            $component = new LunarDateTimeCorrector($paser);
        }

        $this->setComponent($component);
    }

    /**
     * Trả về các thành phần cấu tạo Âm lịch
     * 
     * @return LunarBaseComponentInterface 
     * @throws Exception 
     * @throws Throwable 
     */
    protected function getComponent(): LunarBaseComponentInterface
    {
        if (!$this->component) {
            $this->initComponent();
        }

        return $this->component;
    }

    /**
     * Trả về bộ định dạng thời gian đầu ra
     * @return LunarDateTimeFormatter 
     * @throws Exception 
     * @throws Throwable 
     */
    protected function getFormatter(): LunarDateTimeFormatter
    {
        if (!$this->formatter) {
            $this->formatter = new LunarDateTimeFormatter($this->getComponent());
        }

        return $this->formatter;
    }

    /**
     * Thay đổi các thành phần Âm lịch
     * 
     * @param LunarBaseComponentInterface $component 
     * @return void 
     */
    public function setComponent(LunarBaseComponentInterface $component): void
    {
        $this->component = $component;
    }

    /**
     * {@inheritdoc}
     */
    public function format(string $format): string 
    { 
        return $this->getFormatter()->format($format);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimezone(): DateTimeZone 
    { 
        return (!$this->timezone)
            ? self::getDefaultTimeZone()
            : $this->timezone;
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