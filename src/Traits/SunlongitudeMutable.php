<?php namespace VanTran\LunarCalendar\Traits;

use Exception;
use VanTran\LunarCalendar\Interfaces\SunlongitudeMutableInterface;

/**
 * Cung cấp khả năng biến đổi, tính toán vị trí mới cho các bộ chuyển đổi Kinh độ Mặt trời
 */
trait SunlongitudeMutable
{
    /**
     * @var mixed Góc KDMT tại thời điểm cần tính
     */
    private $degrees;

    /**
     * {@inheritdoc}
     */
    public function getDegrees(bool $withFragtion = false): int|float 
    { 
        if (null === $this->degrees || $this->degrees < 0) {
            $this->degrees = $this->getDegreesFromJd($this->jd);
        }

        return ($withFragtion)
            ? $this->degrees
            : floor($this->degrees);
    }

    /**
     * Cho phép thay đổi góc DKMT
     * 
     * @param float $deg 
     * @return void 
     */
    public function setDegrees(float $deg): void
    {
        $deg = round($deg, 3);
        $this->degrees = $deg;
    }

    /**
     * {@inheritdoc}
     */
    public function add(int|float $deg): SunlongitudeMutableInterface 
    { 
        if ($deg <= 0) {
            throw new Exception("Error. The degrees number of addition must be greater than 0.");
        }

        $currentDeg = $this->getDegrees(true);
        $expectedDeg = $currentDeg + $deg;
        $cycles = floor($expectedDeg / 360);
        $currentJd = $this->getJd();
        $diff = $deg;
        $counter = 0;
        
        do {
            $currentJd += $diff * 0.95;
            $currentDeg = $this->getDegreesFromJd($currentJd);

            $comp = $currentDeg + $cycles * 360;
            $diff = round($expectedDeg - $comp, 3);

            while ($diff < 0) {
                $diff += 360;
            }

            ++$counter;
        } while ($diff > 0.0 && $counter <= 15);

        $new = clone($this);
        $new->setJd($currentJd);
        $new->setDegrees($currentDeg);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function subtract(int|float $deg): SunlongitudeMutableInterface 
    { 
        if ($deg <= 0) {
            throw new Exception("Error. The degrees number must be greater than 0.");
        }

        $currentDeg = $this->getDegrees(true);
        $expectedDeg = $currentDeg - $deg;
        $cycles = floor($expectedDeg / 360);
        $currentJd = $this->getJd();
        $diff = $deg;
        $counter = 0;
        
        do {
            $currentJd -= $diff * 0.95;
            $currentDeg = $this->getDegreesFromJd($currentJd);

            $comp = $currentDeg + $cycles * 360;
            $diff = round(abs($comp - $expectedDeg), 3);

            ++$counter;
        } while ($diff > 0.0 && $counter <= 15);

        $new = clone($this);
        $new->setJd($currentJd);
        $new->setDegrees($currentDeg);

        return $new;
    }
}
