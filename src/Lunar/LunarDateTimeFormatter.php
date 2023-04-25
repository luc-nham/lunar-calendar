<?php namespace VanTran\LunarCalendar\Lunar;

class LunarDateTimeFormatter
{
    /**
     * Bộ đệm truy xuất các định dạng đã được khởi tạo
     * @var array
     */
    protected $caching = [];

    protected $supported = [
        'd', 'j', 'n', 'l', 'm', 'L', 'Y', 't'
    ];

    public function __construct(protected LunarBaseComponentInterface $component)
    {
        
    }

    public function __get(string $name)
    {
        return isset($this->caching[$name])
            ? $this->caching[$name]
            : null;
    }

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

    public function format(string $formater): string
    {
        foreach ($this->supported as $key) {
            while(str_contains($formater, $key)) {
                $formater = str_replace($key, $this->get($key), $formater);
            }
        }

        return $formater;
    }

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
                        if (!$this->isCurrentLeapMonth() && $val < $leap->getMonth()) {
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
            
            default:
                return false;
        }

        return $this->{$key} = $val;
    }
}