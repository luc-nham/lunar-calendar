<?php namespace VanTran\LunarCalendar\Formatters;

use VanTran\LunarCalendar\Interfaces\FormatterInterface;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeComponentInterface;

/**
 * Lớp hỗ trợ định dạng thời gian Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
class LunarDateTimeFormatter implements FormatterInterface
{
    /**
     * @var array Bộ đệm truy xuất các định dạng đã được khởi tạo
     */
    protected $caching = [];

    /**
     * Danh sách các ký tự được hỗ trợ định dạng
     * @var string[]
     */
    protected $supported = [
        'd', // Ngày trong tháng, 2 chữ số có số 0 đứng đầu
        'j', // Ngày trong tháng không có số 0 đứng đầu
        'm', // Biểu thị số của một tháng, có số 0 đứng đầu, nếu tháng nhuận hậu tố  có thêm ký tự '+' (dấu cộng)
        'n', // Biểu thị số của một tháng, không có số 0 đứng đầu, nếu tháng nhuận hậu tố  có thêm ký tự '+' (dấu cộng)
        'l', // Biểu thị số của một tháng, không có số 0 đứng đầu, không xác định tháng nhuận
        'L', // Biểu thị số của một tháng, có số 0 đứng đầu, không xác định tháng nhuận
        'Y', // Biểu thị năm đầy đủ gồm 4 chữ số
        't', // Tổng số ngày trong tháng Âm lịch, có thể là 29 (tháng thiếu) hoặc 30 (tháng đủ)
        'G', // Định dạng 24 giờ của một giờ không có số 0 đứng đầu
        'g', // Định dạng 12 giờ của một giờ không có số 0 đứng đầu
        'H', // Định dạng 24 giờ của một giờ có số 0 đứng đầu
        'h', // Định dạng 12 giờ của một giờ có số 0 đứng đầu
        'i', // Định dạng phút có số 0 đứng đầu
        's', // Định dạng giây có số năm đứng đầu
        'a', // Định dạng chữ thường (tiếng Anh) phân biệt Buổi sáng và Buổi chiều: am hoặc pm
        'A', // Định dạng chữ HOA (tiếng Anh) phân biệt Buổi sáng và Buổi chiều: AM hoặc PM
        'P', // Chênh lệch với giờ Greenwich (GMT) với dấu hai chấm giữa giờ và phút (+07:00)
        'U', // Số giây kể từ Unix Epoch (ngày 1 tháng 1 năm 1970 00:00:00 GMT)
        'Z', // Độ lệch múi giờ tính bằng giây
        'C', // Chuỗi (+) xác định tháng nhuận - kết hợp khi sử dụng 'l' hoặc 'L' thay vì 'm' hoặc 'n'
    ];

    /**
     * Tạo đối tượng mới
     * @param LunarDateTimeComponentInterface $component Lớp chứa các thành phần tính toán Âm
     * @return void 
     */
    public function __construct(protected LunarDateTimeComponentInterface $component) {}

    /**
     * Truy xuất các giá trị trong bộ đệm
     * @param string $name 
     * @return mixed 
     */
    public function __get(string $name)
    {
        return isset($this->caching[$name])
            ? $this->caching[$name]
            : null;
    }

    /**
     * Gán giá trị vào bộ đệm
     * @param string $name 
     * @param string $value 
     * @return void 
     */
    public function __set(string $name, string $value)
    {
        $this->caching[$name] = $value;
    }

    /**
     * Định dạng thời gian Âm lịch
     * @param string $formater 
     * @return string 
     */
    public function format(string $formater): string
    {
        foreach ($this->supported as $key) {
            if(str_contains($formater, $key)) {
                $formater = str_replace($key, $this->get($key), $formater);
            }
        }

        return $formater;
    }

    /**
     * Lấy giá trị từ một ký tự định dạng được hỗ trợ
     * @param string $key 
     * @return false|string 
     */
    public function get(string $key): false|string
    {
        if ($value = $this->{$key}) {
            return $value;
        }

        $comp = $this->component;
        $storage = $comp->getDateTimeStorage();

        switch($key) {
            case 'j':
                return $storage->getDay();

            case 'd':
                $val = str_pad($this->get('j'), 2, '0', STR_PAD_LEFT);
                break;

            case 'l':
                return $storage->getMonth();

            case 'n':
                $val = (function() use ($comp) {
                    $n = $this->get('l');

                    if (
                        $comp->getLeapMonth()->isLeap() &&
                        $comp->getLeapMonth()->getMidnightJd() == $comp->getNewMoon()->getMidnightJd()
                    ) {
                        $n .= '+';
                    }

                    return $n;
                })();
                break;

            case 'm':
                $val = (function() use ($comp) {
                    $pad = 2;

                    if (
                        $comp->getLeapMonth()->isLeap() &&
                        $comp->getLeapMonth()->getMidnightJd() == $comp->getNewMoon()->getMidnightJd()
                    ) {
                        $pad ++;
                    }

                    return str_pad($this->get('n'), $pad, '0', STR_PAD_LEFT);

                })();
                break;

            case 'L':
                $val = str_pad($this->get('l'), 2, '0', STR_PAD_LEFT);
                break;

            case 'Y':
                return $storage->getYear();

            case 't':
                return $comp->getDayOfMonth();

            case 'G':
                return $storage->getHour();

            case 'g':
                return ($this->get('G') > 12)? $this->get('G') % 12 : $this->get('G');

            case 'H':
                $val = str_pad($this->get('G'), 2, '0', STR_PAD_LEFT);
                break;

            case 'h':
                $val = str_pad($this->get('g'), 2, '0', STR_PAD_LEFT);
                break;

            case 'i':
                $val = str_pad($storage->getMinute(), 2, '0', STR_PAD_LEFT);
                break;

            case 's':
                $val = str_pad($storage->getSecond(), 2, '0', STR_PAD_LEFT);
                $val = str_pad($val, 2, '0', STR_PAD_LEFT);
                break;
            
            case 'a':
                return ((int)$this->get('G') < 12)? 'am' : 'pm';

            case 'A':
                return ((int)$this->get('G') < 12)? 'AM' : 'PM';

            case 'P':
                $timezone = $storage->getTimezone();
                return ($timezone) ? $timezone->getName() : '';

            case 'U':
                $val = ($comp->getJd() - $comp::EPOCH_JD) * 86400;
                $val = round($val);
                break;

            case 'Z': 
                return $storage->getOffset();

            case 'C':
                $val = '';

                if (
                    $comp->getLeapMonth()->isLeap() &&
                    $comp->getLeapMonth()->getMidnightJd() == $comp->getNewMoon()->getMidnightJd()
                ) {
                    $val = '(+)';
                }

                break;
            
            default:
                return $key;
        }

        return $this->{$key} = $val;
    }
}