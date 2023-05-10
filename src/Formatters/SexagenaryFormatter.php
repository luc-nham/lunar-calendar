<?php namespace VanTran\LunarCalendar\Formatters;

use VanTran\LunarCalendar\Interfaces\FormatterInterface;
use VanTran\LunarCalendar\Interfaces\SexagenariesInterface;

/**
 * Định dạng hệ thống Can Chi đầu ra
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Formatters
 */
class SexagenaryFormatter implements FormatterInterface
{
    /**
     * @var string[] Nhãn hiển thị cho 10 Can
     */
    private $stems = [
        'a' => 'Giáp',
        'b' => 'Ất',
        'c' => 'Bính',
        'd' => 'Đinh',
        'e' => 'Mậu',
        'f' => 'Kỷ', 
        'g' => 'Canh',
        'h' => 'Tân',
        'i' => 'Nhâm',
        'j' => 'Quý',
    ];

    /**
     * @var string[] Nhãn hiển thị cho 12 Chi
     */
    private $branches = [
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
    ];

    /**
     * Tạo đối tượng mới 
     * 
     * @param SexagenariesInterface $sexagenaries 
     * @return void 
     */
    public function __construct(private SexagenariesInterface $sexagenaries)
    {

    }

    /**
     * Trả về nhãn Can hoặc Chi
     * 
     * @param string $char 
     * @return null|string 
     */
    private function getLabel(string $key): null|string
    {
        $char = $this->sexagenaries->getCharacter($key);
        $terms = (ctype_upper($key))? $this->stems : $this->branches;

        if (isset($terms[$char])) {
            return $terms[$char];
        }
        else{
            return $key;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function format(string $format): string 
    { 
        if (strlen($format) === 1) {
            return $this->getLabel($format);
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
            '/%N/',
            '/%h/',
            '/D\+/',
            '/M\+/',
            '/Y\+/',
            '/H\+/',
        ];

        foreach ($pattern as $item) {
            if (strlen($item) === 4) {
                $key = substr($item, 2, 1);
                $value = $this->getLabel($key);
            }
            else {
                $key = substr($item, 1, 1);
                $value = $this->getLabel($key) . ' ' . $this->getLabel(strtolower($key));
            }

            array_push($replacement, $value);
        }

        return preg_replace($pattern, $replacement, $format);
    }
    
}