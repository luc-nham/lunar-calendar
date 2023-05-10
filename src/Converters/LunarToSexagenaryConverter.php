<?php namespace VanTran\LunarCalendar\Converters;

use Exception;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeInteface;
use VanTran\LunarCalendar\Interfaces\SexagenariesInterface;

class LunarToSexagenaryConverter implements SexagenariesInterface
{
    /**
     * @var float Số ngày Julian giả định theo giờ địa phương
     */
    private $jd;

    /**
     * @var array Bộ đệm đơn giản cho các giá trị index đã được tính toán
     */
    private $indexes = [];

    public function __construct(private LunarDateTimeInteface $lunar)
    {
        $this->jd = $this->getFakeJd();
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
     * Làm giả, biến đổi số ngày Julian theo UTC thành giờ địa phương. Giá trị này giúp các phương thức chuyển đổi được
     * thực hiện dễ dàng hơn, nhưng không nên được sử dụng bên ngoài lớp.
     * 
     * @return int 
     */
    private function getFakeJd(): int
    {
        $jd = floor($this->lunar->getMidnightJd() + $this->lunar->getOffset() / 86400 + 1);

        if ($this->lunar->format('H') == 23) {
            $jd += 1;
        }

        return $jd;
    }

    /**
     * Trả về số chỉ mục của hệ thống Can Chi. Chỉ mục được xác định như sau:
     * - 10 Can bắt đầu 0 là Giáp, đếm thuận Ất, Bính... đến Quý là 9.
     * - 12 Chi bắt đầu 0 là Tý, đếm thuận Sửu, Dần, Mão... đến Hợi là 11.
     *  
     * @param string $char 
     * @return null|int 
     */
    public function getIndex(string $key): string|int
    {
        if ($this->{$key}) {
            return $this->{$key};
        }

        if (strlen($key) !== 1 || !ctype_alpha($key)) {
            throw new Exception("Error. The key should be only one alphabet character."); 
        }

        switch ($key) {
            case 'D':
                $val = ($this->jd + 9) % 10;
                break;

            case 'd':
                $val = ($this->jd + 1) % 12;
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

            case 'N':
                $val = (function() {
                    $D = $this->getIndex('D');
                    $N = $compare = 0;

                    while ($compare !== $D) {
                        $compare = ($compare + 1) % 10;
                        $N = ($N + 2) % 10;  
                    }

                    return $N;
                })();

                break;

            case 'H':
                $val = (function() {
                    $H = 23;
                    $N = $this->getIndex('N');
                    $currentHour = $this->lunar->format('H');

                    while($H != $currentHour) {
                        $H = ($H + 1) % 24;
                        
                        if($H % 2 != 0) {
                            $N = ($N + 1) % 10;
                        }
                    }

                    return $N;
                })();

                break;

            case 'h':
                $val = (function() {
                    $com = 23;
                    $h = 0;
                    $currentHour = $this->lunar->format('H');

                    while($com != $currentHour) {
                        $com = ($com + 1) % 24;
                        
                        if($com % 2 != 0) {
                            $h = ($h + 1) % 12;
                        }
                    }

                    return $h;
                })();

                break;
            
            default:
                return $key;
        }

        return $this->{$key} = $val;
    }

    /**
     * Trả về ký tự đại diện cho Can hoặc Chi:
     * - Trả về a, b..,i, j tương ứng với Giáp, Ất.., Nhâm, Quý
     * - Trả về a,b.., k,l tương ứng với Tý, Sửu.., Tuất, Hợi
     * 
     * @param string $key 
     * @return string 
     * @throws Exception 
     */
    public function getCharacter(string $key): string
    {
        $index = $this->getIndex($key);

        if ($index === $key) {
            return $key;
        }

        $chars = (ctype_upper($key)) ? range('a', 'j') : range('a', 'l');

        return $chars[$index];
    }
}