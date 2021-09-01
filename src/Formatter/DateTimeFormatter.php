<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class DateTimeFormatter implements DateTimeInterface
{
    protected $d;
    protected $m;
    protected $Y;
    protected $H;
    protected $i;
    protected $s;
    protected $timeZone;

    public static function create(): self
    {
        return new static();
    }

    public function __construct(int $d = 1, int $m = 1, int $Y = 1970, int $H = 0, int $i = 0, int $s = 0, float $timeZone = 0)
    {
        $this->d = $d;
        $this->m = $m;
        $this->Y = $Y;
        $this->H = $H;
        $this->i = $i;
        $this->s = $s;
        $this->timeZone = $timeZone;
    }
    
    public function setDate(int $d = 0, int $m = 0, int $Y = 0): self
    {
        if($d) {
            $this->d = $d;
        }

        if($m) {
            $this->m = $m;
        }

        if($Y) {
            $this->Y = $Y;
        }

        return $this;
    }

    public function setTime(int $H = 0, int $i = 0, int $s = 0): self
    {
        if($H) {
            $this->H = $H;
        }

        if($i) {
            $this->i = $i;
        }

        if($s) {
            $this->s = $s;
        }

        return $this;
    }

    public function setTimeZone(float $timeZone): self
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    public function getDate(string $key): int
    {
        $skey = strtolower($key);

        try {
            switch($skey) {
                case 'd': 
                    return $this->d;
                case 'm':
                    return $this->m;
                case 'y':
                    return $this->Y;
            }

            throw new \Exception("Invalid input key: $key");
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getTime(string $key): int
    {
        $skey = strtolower($key);

        try {
            switch($skey) {
                case 'h':
                    return $this->H;

                case 'i':
                    return $this->i;

                case 's':
                    return $this->s;
            }

            throw new \Exception("Invalid input key: $key");
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getTimeZone(): float
    {
        return $this->timeZone;
    }
}