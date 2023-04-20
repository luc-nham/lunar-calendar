<?php namespace VanTran\LunarCalendar\Lunar;

use Exception;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\NewMoonPhaseInterface;
use VanTran\LunarCalendar\Sunlongitude\BaseSunlongitude;

class LunarLeapMonth implements LunarLeapMonthInterface
{
    protected $offset;

    protected $newMoon;

    public function __construct(protected Lunar11thNewMoonPhaseInterface $newMoon11th)
    {
        
    }

    /**
     * Xác định năm Âm lịch có thể có tháng nhuận hay không. Nếu một năm chia hết cho 19 hoặc dư 3, 6, 9, 11, 14 và 17
     * thì năm Âm lịch đó sẽ có tháng nhuận.
     * 
     * @return bool 
     */
    public function canYearBeLeap(): bool
    {
        return in_array(
            $this->newMoon11th->getYear() % 19,
            [0, 3, 6, 9, 11, 14, 17]
        );
    }

    /**
     * Xác định 1 tháng có thể trở thành tháng nhuận hay không dựa vào điểm Sóc của tháng đó và điểm Sóc của tháng kế
     * tiếp. 1 tháng có thể trở thành tháng nhuận khi nó không chứa điểm Trung khí nào.
     * 
     * @param NewMoonPhase $newMoon 
     * @param NewMoonPhase $nextNewMoon 
     * @return bool 
     */
    public function canMonthBeLeap(NewMoonPhaseInterface $newMoon, NewMoonPhaseInterface $nextNewMoon): bool
    {
        $leap = false;
        $sunLongitude = new BaseSunlongitude($newMoon->getMidnightJd(), 0);
        $nexSunLongitude = new BaseSunlongitude($nextNewMoon->getMidnightJd(), 0);

        if ($sunLongitude->getDegrees() / 30 == $nexSunLongitude->getDegrees() / 30) {
            $leap = true;
        }

        return $leap;
    }

    /**
     * Thu thập các tháng có thể trở thành tháng nhuận trong năm
     * @return array 
     */
    protected function collectMonthsCanBeLeap(): array
    {
        $offset = 12;
        $months = [];

        $nextNewMoon = $this->newMoon11th->add(2);

        for ($i = 0; $i < 13; $i ++) {
            $newMoon = $nextNewMoon->subtract(1);

            if ($this->canMonthBeLeap($newMoon, $nextNewMoon)) {
                $months[] = [
                    'offset' => $offset,
                    'new_moon' => $newMoon
                ];
            }

            $nextNewMoon = $newMoon;
            $offset --;
        }

        return $months;
    }

    /**
     * Khởi tạo các thuộc tính dữ liệu
     * 
     * @return void 
     * @throws Exception 
     */
    protected function init(): void
    {
        if (!$this->canYearBeLeap()) {
            return;
        }

        $leaps = $this->collectMonthsCanBeLeap();
        $counter = count($leaps);

        if ($counter == 0) {
            throw new Exception("Error. Can not find lunar months can be leap.");
            
        }

        if ($counter  > 1) {
            $this->offset = 11;
            $this->newMoon = $this->newMoon11th->add(1);
        }
        else {
            $this->offset = $leaps[0]['offset'];
            $this->newMoon = $leaps[0]['new_moon'];
        }
}

    /**
     * Trả về vị trí tháng nhuận Âm lịch (số tháng)
     * @return int|false 
     */
    public function getOffset(): int|false
    {
        return ($this->offset) ? $this->offset : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewMoon(): ?NewMoonPhaseInterface
    {
        return $this->newMoon;
    }

    /**
     * {@inheritdoc}
     */
    public function isLeap(): bool 
    { 
        return ($this->offset) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getMonthOffset(): false|int 
    { 
        return ($this->offset) ? $this->offset : false;
    }
}