<?php namespace VanTran\LunarCalendar\Sexagenary;

use Closure;
use Exception;
use VanTran\LunarCalendar\LunarDateTimeInteface;

class SexagenaryFormatter
{
    public const LABEL = 0;
    public const NUMBER = 1;
    public const CHARACTER = 2;

    /**
     * Ký tự đại diện và nhãn mặc định cho hệ thống Can Chi
     * @var string[][]
     */
    private $terms = [
        'stems' => [
            'A' => 'Giáp',
            'B' => 'Ất',
            'C' => 'Bính',
            'D' => 'Đinh',
            'E' => 'Mậu',
            'F' => 'Kỷ',
            'G' => 'Canh',
            'H' => 'Tân',
            'I' => 'Nhâm',
            'J' => 'Quý' 
        ],
        'branches' => [
            'a' => 'Tý',
            'b' => 'Sửu',
            'c' => 'Dần',
            'd' => 'Mão',
            'e' => 'Thìn',
            'f' => 'Tị', 
            'g' => 'Ngọ',
            'h' => 'Mùi',
            'i' => 'Thân',
            'j' => 'Dậu',
            'k' => 'Tuất',
            'l' => 'Hợi'
        ]
    ];

    /**
     * @var float Số ngày MJD của điểm Âm lịch theo giờ địa phương, không phải UTC
     */
    private $localJd;

    /**
     * Bộ đệm đơn giản cho các giá trị index đã được tính toán
     * @var array
     */
    private $indexes = [];

    public function __construct(private LunarDateTimeInteface $lunar)
    {
        $this->localJd = floor($lunar->getMidnightJd() + $this->lunar->getOffset() / 86400);
    }

    /**
     * Truy xuất các giá trị trong bộ đệm
     * @param string $name 
     * @return mixed 
     */
    public function __get(string $name)
    {
        return isset($this->indexes[$name])
            ? $this->indexes[$name]
            : null;
    }

    /**
     * Gán giá trị vào bộ đệm
     * @param string $name 
     * @param int $value 
     * @return void 
     */
    public function __set(string $name, int $value)
    {
        $this->indexes[$name] = $value;
    }

    /**
     * 
     * @param Closure $closure 
     * @return int 
     */
    protected function calculateIndex(Closure $closure): int 
    {
        return $closure();
    }

    /**
     * Định dạng đầu ra cho các mốc thời gian Can Chi
     * 
     * @param string $format 
     * @param int $type 
     * @return string|int 
     */
    public function format(string $format, int $type = self::LABEL): string|int
    {
        switch ($type) {
            case self::NUMBER:
                $func = 'getIndex';
                break;
            
            case self::CHARACTER:
                $func = 'getChar';
                break;

            case self::LABEL:
                $func = 'getLabel';
                break;

            default:
                throw new Exception("Invalid format type");
        }

        if (strlen($format) === 1) {
            $val = call_user_func([$this, $func], $format);
            
            return ($val) ? $val : $format;
        }

        $replacement = [];
        $pattern = [
            '/%D/',
            '/%d/',
            '/%M/',
            '/%m/',
            '/%Y/',
            '/%y/',
            '/%H/',
            '/%h/',
            '/D\+/',
            '/M\+/',
            '/Y\+/',
            '/H\+/',
        ];

        foreach ($pattern as $item) {
            if (strlen($item) === 4) {
                $key = substr($item, 2, 1);
                $value = call_user_func([$this, $func], $key);
            }
            else {
                $key = substr($item, 1, 1);
                $value = call_user_func([$this, $func], $key) . ' ' . call_user_func([$this, $func], strtolower($key));
            }

            array_push($replacement, $value);
        }

        return preg_replace($pattern, $replacement, $format);
    }

    /**
     * Trả về index của hệ thống Can Chi
     * @param string $char 
     * @return null|int 
     */
    public function getIndex(string $char): null|int
    {
        if ($this->{$char}) {
            return $this->{$char};
        }

        switch ($char) {
            case 'D':
                $val = ($this->localJd + 9) % 10;
                break;

            case 'd':
                $val = ($this->localJd + 1) % 12;
                break;

            case 'M':
                $val = ($this->lunar->format('Y') * 12 + $this->lunar->format('l') + 3) % 10;
                break;

            case 'm': 
                $val = ($this->lunar->format('l') + 1) % 12;
                break;

            case 'Y':
                $val = ($this->lunar->format('Y') + 6) % 10;
                break;

            case 'y':
                $val = ($this->lunar->format('Y') + 8) % 12;
                break;
            
            default:
                return null;
        }

        return $this->{$char} = $val;
    }

    /**
     * Trả về ký tự đại diện của Can hoặc Chi
     * 
     * @param string $char 
     * @return null|int 
     */
    public function getChar(string $char): null|string
    {
        if (!$index = $this->getIndex($char)) {
            return null;
        }

        $chars = (ctype_upper($char)) ? range('A', 'J') : range('a', 'l');
        return $chars[$index];
    }

    /**
     * Trả về nhãn Can hoặc Chi
     * 
     * @param string $char 
     * @return null|string 
     */
    public function getLabel(string $char): null|string
    {
        if (!$rep = $this->getChar($char)) {
            return null;
        } 

        $term = (ctype_upper($char)) ? $this->terms['stems'] : $this->terms['branches'];
        return $term[$rep];
    }
}