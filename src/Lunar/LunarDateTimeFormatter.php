<?php namespace VanTran\LunarCalendar\Lunar;

/**
 * Lớp hỗ trợ định dạng thời gian Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
class LunarDateTimeFormatter
{
    /**
     * Bộ đệm truy xuất các định dạng đã được khởi tạo
     * @var array
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
     * @param LunarBaseComponentInterface $component Lớp chứa các thành phần tính toán Âm
     * @return void 
     */
    public function __construct(protected LunarBaseComponentInterface $component) {}

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
     * Kiểm tra thời điểm đầu vào có phải tháng nhuận không
     * @return bool 
     */
    protected function isCurrentLeapMonth(): bool
    {
        if (!$this->component->getLeapMonth()->isLeap()) {
            return false;
        }

        $leap = $this->component->getLeapMonth();
        $nm = $this->component->getNewMoon();

        if ($leap->getMidnightJd() !== $nm->getMidnightJd()) {
            return false;
        }

        return true;
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

        switch($key) {
            case 'j':
                $val = 1 + floor($comp->getMidnightJd() - $comp->getNewMoon()->getMidnightJd());
                break;

            case 'd':
                $val = str_pad($this->get('j'), 2, '0', STR_PAD_LEFT);
                break;

            case 'l':
                $phases = $comp->get11thNewMoon()->getTotalCycles() - $comp->getNewMoon()->getTotalCycles();
                $leap = $comp->getLeapMonth();

                if ($phases === 0) {
                    $val = 11;
                } elseif ($phases < 0) {
                    $val = 12;

                    if ($phases === -1 && $leap->getMonth() === 11) {
                        $val = 11;
                    }
                } else {
                    $val = 11 - $phases;

                    if ($leap->isLeap()) {
                        if (!$this->isCurrentLeapMonth() && $val < $leap->getMonth() && $leap->getMonth() != 11) {
                            $val --;
                        }
                    }
                }

                break;

            case 'n':
                $val = ($this->isCurrentLeapMonth()) ? $this->get('l') . '+' : $this->get('l');
                break;

            case 'm':
                $pad = ($this->isCurrentLeapMonth()) ? 3 : 2;
                $val = str_pad($this->get('n'), $pad, '0', STR_PAD_LEFT);
                break;

            case 'L':
                $val = str_pad($this->get('l'), 2, '0', STR_PAD_LEFT);
                break;

            case 'Y':
                $val = $comp->get11thNewMoon()->getYear();
                break;

            case 't':
                $val = $comp->getDayOfMonth();
                break;

            case 'G':
                $val = round($comp->getDiffJd() * 24, 0) % 24;
                break;

            case 'g':
                $val = ($this->get('G') > 12)? $this->get('G') % 12 : $this->get('G');
                break;

            case 'H':
                $val = str_pad($this->get('G'), 2, '0', STR_PAD_LEFT);
                break;

            case 'h':
                $val = str_pad($this->get('g'), 2, '0', STR_PAD_LEFT);
                break;

            case 'i':
                $val = round($comp->getDiffJd() * 60 * 24, PHP_ROUND_HALF_DOWN) % 60;
                $val = str_pad($val, 2, '0', STR_PAD_LEFT);
                break;

            case 's':
                $val = round($comp->getDiffJd() * 3600 * 24) % 60;
                $val = str_pad($val, 2, '0', STR_PAD_LEFT);
                break;
            
            case 'a':
                $val = ((int)$this->get('G') < 12)? 'am' : 'pm';
                break;

            case 'A':
                $val = ((int)$this->get('G') < 12)? 'AM' : 'PM';
                break;

            case 'P':
                return $comp->getTimeZone()->getName();

            case 'U':
                $val = ($comp->getJd() - $comp::EPOCH_MJD) * 86400;
                $val = round($val);
                break;

            case 'Z': 
                return $comp->getOffset();

            case 'C':
                $val = ($this->isCurrentLeapMonth())? '(+)' : '';
                break;
            
            default:
                return false;
        }

        return $this->{$key} = $val;
    }
}