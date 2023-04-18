<?php namespace VanTran\LunarCalendar\Lunar;

use DateTimeZone;

/**
 * Trình phân tích chuỗi thời gian Âm lịch và trả về các phân mảnh dữ liệu đơn để sử dụng cho nhiều mục đích khác nhau.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
class LunarParser implements LunarInputInterface
{
    /**
     * @var string Múi giờ địa phương mặc định cho Âm lịch Việt Nam
     */
    public const DEFAULT_TIMEZONE = '+07:00';

    /**
     * @var int Phần bù UTC mặc định
     */
    public const DEFAULT_OFFSET = 25200;

    /**
     * @var int Năm gồm 4 chữ số
     */
    protected $year;

    /**
     * @var int tháng từ 1 đến 12
     */
    protected $month;

    /**
     * @var int Ngày từ 1 đến 30 (Âm lịch không có 31 ngày)
     */
    protected $day;

    /**
     * @var int Giờ từ 0 đến 23
     */
    protected $hour;

    /**
     * @var int Phút từ 0 đến 59
     */
    protected $minute;

    /**
     * @var int Giây từ 0 đến 59
     */
    protected $second;

    /**
     * @var bool Xác định có phải tháng nhuận
     */
    protected $leap = false;

    /**
     * @var int Bù chênh lệnh UTC, tính bằng giây
     */
    protected $offset;

    /**
     * @var array Các cảnh báo trong quá trình phân tích
     */
    protected $warnings = [];

    /**
     * @var array Lỗi trong quá trình phân tích
     */
    protected $errors = [];

    /**
     * Tạo đối tượng mới
     * 
     * @param string $datetime 
     * @param null|DateTimeZone $timezone 
     * @return void 
     */
    public function __construct(protected string $datetime, protected ?DateTimeZone $timezone = null)
    {
        $this->parse();
    }

    /**
     * Thực hiện phân tích cú pháp chuỗi Âm lịch đầu vào
     * 
     * @return void 
     */
    protected function parse(): void
    {
        $datetime = $this->datetime;
        
        // Các trình tự thực hiện phân tích như dưới đây
        $this->parseLeapMonth($datetime);
        $this->parserSlashedFormat($datetime);
        $this->parseDateTime($datetime);
    }

     /**
     * Phân tích cú pháp chuỗi thời gian có chứa tháng nhuận. Hỗ trợ 2 loại định dạng gồm dấu '+' ở sau tháng nhuận hoặc
     * chuỗi '(+)' ở bất kỳ vị trí nào:
     * - (d/m/Y): vd, 03/02+/2022 (ngày mùng 3 tháng 2 nhuận năm 2022)
     * - (d/l/Y c): vd, 06/05/2031 (+) (ngày 06 tháng 05 năm 2031 nhuận). Ký tự '+' phải được đặt bên trong cặp dấu ngoặc
     * đơn '()' để tránh trường hợp lỗi bất ngờ do chuỗi thời gian có chứa thông tin múi giờ dương. Vd về thời gian đầy đủ
     * bao gồm cả tháng nhuận và múi giờ: 17/05/1990 20:30:00 +07:00 (+) - Ngày 17 tháng 5 (nhuận) năm 1990, lúc 20 giờ 
     * 30 phút, múi giờ GMT+7
     * 
     * @return void 
     */
    protected function parseLeapMonth(string &$datetime): void
    {
        $leapSign = '(+)';

        if (str_contains($this->datetime, $leapSign)) {
            $this->leap = true;
            $datetime = str_replace($leapSign, '', $datetime);
        }
        else {
            $pattern = '/[0-9]{1,2}\+[\/|\-|\.]/';
            preg_match($pattern, $datetime, $matches);
    
            if (count($matches) == 0) {
                return;
            }
    
            $replacement = str_replace('+', '', $matches[0]);
    
            $datetime = str_replace($matches[0], $replacement, $datetime);
            $this->leap = true;
        }
    }

    /**
     * Khi sử dụng dấu gạch chéo '/' để phân tách thời gian, chẳng hạn 01/05/2020 (ngày 01 tháng 05 năm 2020), khi sử
     * dụng date_parse() của PHP, nó sẽ hiểu rằng 01 là tháng, 05 là năm. Điều này dẫn đến không đồng nhất với cách viết
     * Âm lịch của người Việt và gây ra sự khó hiểu, thiếu nhất quán. Trong trường hợp này, hàm sau đây sẽ chuyển chuỗi 
     * thời gian đầu vào từ định dạng có chứa d/m/Y thành chuỗi mà có định dạng có chứa d-m-Y. Nghĩa là, nó sẽ thay thế
     * các dấu gạch chéo thành dấu gạch ngang ở phần định dạng ngày, tháng, năm. 
     * 
     * @param string $datetime 
     * @return void 
     */
    protected function parserSlashedFormat(string &$datetime): void
    {
        $pattern = '/[0-9]{1,4}[\/]/';
        preg_match_all($pattern, $datetime, $ouput);

        if (!empty($ouput)) {
            foreach ($ouput as $key => $item) {
                $item = str_replace('/', '-', $item);
                $datetime = str_replace($ouput[$key], $item, $datetime);
            }
        }
    }

    protected function parseDateTime(string &$datetime): void
    {
        $data = date_parse($datetime);

        $this->warnings = $data['warnings'];
        $this->errors = $data['errors'];

        foreach (['year', 'month', 'day'] as $key) {
            if (!$data[$key]) {
                $this->errors[] = ucfirst($key) . ' is required';
            }
            else {
                $this->{$key} = $data[$key];
            }
        }

        $this->hour = (int)$data['hour'];
        $this->minute = (int)$data['minute'];
        $this->second = (int)$data['second'];

        // Phân tích các thông tin múi giờ
        if (!$this->hasError()) {
            $this->parseZone($data);
        }
    }

    /**
     * Trả về phần bù UTC từ một đối DateTimeZone
     * 
     * @param DateTimeZone $timezone
     * @return int 
     */
    protected function getOffsetFromTimeZone(DateTimeZone $timezone): int
    {
        $transitions = $timezone->getTransitions();
        $offset = 0;

        if (false === $transitions) {
            $zone = explode(':', $timezone->getName());
            
            if (count($zone) == 2) {
                $offset = floatval($zone[0]) * 3600;
                $decimal = floatval($zone[1]) * 60;

                if ($offset < 0) {
                    $decimal *= -1;
                }

                $offset += $decimal;
            }
        }
        else {
            $transitions = array_reverse($transitions);

            foreach ($transitions as $transition) {
                $time = explode('-', $transition['time']);
                $year = $time[0];

                if ($this->getYear() >= $year) {
                    $offset = $transition['offset'];

                    break;
                }
            }
        }

        return $offset;
    }

    /**
     * Phân tích các thông tin về múi giờ địa phương và phần bù UTC
     * 
     * @return void 
     */
    protected function parseZone(array &$data): void
    {
        if ($this->timezone) {
            $this->offset = $this->getOffsetFromTimeZone($this->timezone);
        }
        else {
            if (
                isset($data['zone']) &&
                isset($data['zone_type']) &&
                $data['zone_type'] == 1            
            ) {
                $this->offset = $data['zone'];
                $h = $this->offset / 3600;
                $d = $h - floor($h);

                $h = ($h >= 0)
                    ? str_pad($h, 3, '+0', STR_PAD_LEFT)
                    : str_pad(abs($h), 3, '-0', STR_PAD_LEFT);

                $tz = $h . ':' . str_pad($d, 2, '0', STR_PAD_LEFT);
                $this->timezone = new DateTimeZone($tz);
            }
            elseif (
                isset($data['zone_type']) && 
                isset($data['tz_id'])
            ) {
                $this->timezone = new DateTimeZone($data['tz_id']);
                $this->offset = $this->getOffsetFromTimeZone($this->timezone);
            }
            else {
                $this->offset = self::DEFAULT_OFFSET;
                $this->timezone = new DateTimeZone(self::DEFAULT_TIMEZONE);
            }
        }
    }

    /**
     * Trả về true nếu có lỗi trong quá trình phân tích cú pháp, false nếu không
     * 
     * @return bool 
     */
    public function hasError(): bool
    {
        return (empty($this->errors)) ? false : true;
    }

    /**
     * Trả về mảng các lỗi phân tích cú pháp
     * 
     * @return array 
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getSecond(): int
    {
        return $this->second;
    }

    /**
     * {@inheritdoc}
     * @return bool 
     */
    public function isLeapMonth(): bool
    {
        return $this->leap;
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getOffset(): int
    {
        return ($this->offset) ? $this->offset : 0;
    }

    /**
     * Trả về múi giờ địa phương
     * 
     * @return null|DateTimeZone 
     */
    public function getTimeZone(): ?DateTimeZone
    {
        return $this->timezone;
    }
}