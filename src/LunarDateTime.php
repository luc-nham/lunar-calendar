<?php namespace VanTran\LunarCalendar;

use DateTime;
use DateTimeZone;
use Exception;
use Throwable;
use VanTran\LunarCalendar\Correctors\GregorianToLunarCorrector;
use VanTran\LunarCalendar\Correctors\LunarDateTimeCorrector;
use VanTran\LunarCalendar\Formatters\LunarDateTimeFormatter;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeComponentInterface;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeInteface;
use VanTran\LunarCalendar\Parsers\LunarDateTimeParser;
use VanTran\LunarCalendar\Storages\GregorianToLunarStorageMutable;

/**
 * Ngày tháng Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar
 */
class LunarDateTime implements LunarDateTimeInteface
{
    /**
     * Xác định kiểu chuỗi thời gian đầu vào là Âm lịch
     */
    public const LUNAR_INPUT = 1;

    /**
     * Xác định kiểu chuỗi thời gian đầu vào là Âm lịch
     */
    public const GREGORIAN_INPUT = 2;

    /**
     * @var LunarDateTimeComponentInterface
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
     * @param null|DateTimeZone $timezone Múi giờ địa phương
     * @param int $type Xác định kiểu dữ liệu thời gian đầu vào là Âm lịch (1) hay Dương lịch (2)
     * @return void 
     */
    public function __construct(
        private $datetime = 'now', 
        private ?DateTimeZone $timezone = null, 
        private int $type = self::LUNAR_INPUT)
    {
        $this->initComponent();
    }

    /**
     * Khởi tạo nhanh ngày tháng Âm lịch
     * 
     * @param null|DateTimeZone $timezone Múi giờ địa phương
     * @return LunarDateTime 
     */
    public static function now(?DateTimeZone $timezone = null): LunarDateTime
    {
        return new self('now', $timezone);
    }

    /**
     * Khởi tạo / chuyển đổi một mốc thời gian Dương lịch sang Âm lịch
     * 
     * @param string $datetime 
     * @param null|DateTimeZone $timezone 
     * @return LunarDateTime 
     */
    public static function createFromGregorian(string $datetime, ?DateTimeZone $timezone = null): self
    {
        return new self($datetime, $timezone, self::GREGORIAN_INPUT);
    }

    /**
     * Khởi tạo các thành phần cấu tạo Âm lịch
     * @return void 
     * @throws Exception 
     */
    private function initComponent(): void
    {
        $datetime = $this->datetime;

        if ($datetime === 'now' || $datetime === '' || $this->type === self::GREGORIAN_INPUT) {
            $date = new DateTime($datetime);

            if ($this->getTimezone()) {
                $date->setTimezone($this->getTimezone());
            }
            else {
                $this->timezone = $date->getTimezone();
            }

            $input = new GregorianToLunarStorageMutable($date);
            
            $this->gregorian = $date;
            $component = new GregorianToLunarCorrector($input);
        }
        else {
            $paser = new LunarDateTimeParser($datetime, $this->getTimezone());

            if (!$this->timezone && $paser->getTimezone()) {
                $this->timezone = $paser->getTimezone();
            }

            if ($paser->hasError()) {
                throw new Exception("Parse error. Lunar date time invalid.");
            }

            $component = new LunarDateTimeCorrector($paser);
        }

        $this->component = $component;
    }

    /**
     * Trả về các thành phần cấu tạo và thời gian Âm lịch đã được hợp lệ hóa
     * 
     * @return LunarDateTimeComponentInterface 
     */
    private function getComponent(): LunarDateTimeComponentInterface
    {
        return $this->component;
    }

    /**
     * Trả về bộ định dạng thời gian đầu ra
     * 
     * @return LunarDateTimeFormatter 
     * @throws Exception 
     * @throws Throwable 
     */
    protected function getFormatter(): LunarDateTimeFormatter
    {
        if (!$this->formatter) {
            try {
                $component = $this->getComponent();
                $this->formatter = new LunarDateTimeFormatter($component);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return $this->formatter;
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
    public function getTimezone(): ?DateTimeZone 
    { 
        return $this->timezone;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int 
    { 
        return $this->getComponent()->getOffset();
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
        return $this->getComponent()->getJd();
    }

    /**
     * {@inheritdoc}
     */
    public function getMidnightJd(): float
    { 
        return $this->getComponent()->getMidnightJd();
    }
}