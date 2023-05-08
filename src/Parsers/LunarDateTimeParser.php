<?php namespace VanTran\LunarCalendar\Parsers;

use DateTimeZone;
use VanTran\LunarCalendar\Storages\LunarDateTimeStorageMutable;

/**
 * Trình phân tích cú pháp chuỗi thời gian Âm lịch đầu vào thành các mảnh dữ liệu, cung cấp cho các bộ chuyển đổi khả
 * năng truy xuất trực tiếp giá trị riêng lẻ.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Parsers
 */
class LunarDateTimeParser extends LunarDateTimeStorageMutable
{
    /**
     * @var array Các cảnh báo trong quá trình phân tích
     */
    private $warnings = [];

    /**
     * @var array Lỗi trong quá trình phân tích
     */
    private $errors = [];

    /**
     * Tạo đối tượng mới
     * 
     * @param string $datetime 
     * @param null|DateTimeZone $timezone 
     * @return void 
     */
    public function __construct(private string $datetime, ?DateTimeZone $timezone = null)
    {
        if ($timezone) {
            $this->setTimeZone($timezone);
        }

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
            $datetime = str_replace($leapSign, '', $datetime);
            $this->setIsLeapMonth(true);
        }
        else {
            $pattern = '/[0-9]{1,2}\+[\/|\-|\.]/';
            preg_match($pattern, $datetime, $matches);
    
            if (count($matches) == 0) {
                return;
            }
    
            $replacement = str_replace('+', '', $matches[0]);
    
            $datetime = str_replace($matches[0], $replacement, $datetime);
            $this->setIsLeapMonth(true);
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

    /**
     * Phân tích chuỗi thời gian
     * @param string $datetime 
     * @return void 
     */
    protected function parseDateTime(string &$datetime): void
    {
        $data = date_parse($datetime);

        $this->warnings = $data['warnings'];
        $this->errors = $data['errors'];

        foreach (['year', 'month', 'day'] as $key) {
            $val = $data[$key];

            if (!$val) {
                $this->errors[] = ucfirst($key) . ' is required';
            }
            else {
                if ($key == 'year') {
                    $this->setYear($val);
                } elseif ($key == 'month') {
                    $this->setMonth($val);
                } else {
                    $this->setDay($val);
                }
            }
        }

        $this->setHour((int)$data['hour']);
        $this->setMinute((int)$data['minute']);
        $this->setSecond((int)$data['second']);

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
            $offset = $this->getOffsetFromTimeZone($this->getTimeZone());
            $this->setOffset($offset);
        }
        else {
            if (
                isset($data['zone']) &&
                isset($data['zone_type']) &&
                $data['zone_type'] == 1            
            ) {
                $this->setOffset($data['zone']);
                $h = $this->getOffset() / 3600;
                $d = abs($h - floor($h));

                $h = ($h >= 0)
                    ? str_pad($h, 3, '+0', STR_PAD_LEFT)
                    : str_pad(abs($h), 3, '-0', STR_PAD_LEFT);

                $tz = $h . ':' . str_pad($d, 2, '0', STR_PAD_LEFT);
                $this->setTimeZone(new DateTimeZone($tz));
            }
            elseif (
                isset($data['zone_type']) && 
                isset($data['tz_id'])
            ) {
                $this->setTimeZone(new DateTimeZone($data['tz_id']));
                $this->setOffset($this->getOffsetFromTimeZone($this->getTimeZone()));
            }
            else {
                $this->setOffset(0);
                $this->setTimeZone(new DateTimeZone('+0000'));
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
     * Trả về mảng các cảnh báo phân tích cú pháp
     * @return array 
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }
}