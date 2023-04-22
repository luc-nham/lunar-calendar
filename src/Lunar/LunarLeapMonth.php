<?php namespace VanTran\LunarCalendar\Lunar;

use Exception;
use VanTran\LunarCalendar\Mjd\BaseMjd;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\NewMoonPhaseInterface;
use VanTran\LunarCalendar\Sunlongitude\MjdToSunlongitude;

class LunarLeapMonth extends BaseMjd implements LunarLeapMonthInterface
{
    /**
     * @var null|int Vị trí (số) tháng nhuận Âm lịch nếu có
     */
    protected $month;

    /**
     * Tạo đối tượng mới
     * 
     * @param Lunar11thNewMoonPhaseInterface $newMoon11th Điểm sóc tháng 11 của năm Âm lịch
     * @return void 
     * @throws Exception 
     */
    public function __construct(protected Lunar11thNewMoonPhaseInterface $newMoon11th)
    {
        $this->offset = $newMoon11th->getOffset();
        $this->init();
    }

    /**
     * Xác định 1 tháng có thể trở thành tháng nhuận hay không dựa vào điểm Sóc của tháng đó và điểm Sóc của tháng kế
     * tiếp. 1 tháng có thể trở thành tháng nhuận khi nó không chứa điểm Trung khí nào.
     * 
     * @param NewMoonPhaseInterface $newMoon 
     * @param NewMoonPhaseInterface $nextNewMoon 
     * @return bool 
     */
    public function canMonthBeLeap(NewMoonPhaseInterface $newMoon, NewMoonPhaseInterface $nextNewMoon): bool
    {
        $leap = false;
        $sl = new MjdToSunlongitude($newMoon);
        $nexSl = new MjdToSunlongitude($nextNewMoon);

        if (floor($sl->getMidnightDegrees() / 30) == floor($nexSl->getMidnightDegrees() / 30)) {
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
        $month = 12;
        $months = [];

        $nextNewMoon = $this->newMoon11th->add(2);

        for ($i = 0; $i < 13; $i ++) {
            $newMoon = $nextNewMoon->subtract(1);

            if ($this->canMonthBeLeap($newMoon, $nextNewMoon)) {
                $months[] = [
                    'month' => $month,
                    'new_moon' => $newMoon
                ];
            }

            $nextNewMoon = $newMoon;
            $month --;
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
        if (!$this->isLeap()) {
            return;
        }

        $leaps = $this->collectMonthsCanBeLeap();
        $counter = count($leaps);

        // Trường hợp năm có thể nhuận, nhưng không thể tìm được tháng nhuận, cần kiểm tra lại phương pháp tính
        if ($counter == 0) {
            throw new Exception("Error. Can not find lunar months can be leap.");
        }

        // Trường hợp tìm được nhiều hơn 1 kết quả, thì năm nhuận là tháng 11 Âm lịch
        if ($counter > 1) {
            $this->month = 11;
            $this->jd = $this->newMoon11th->add(1)->getJd();
        }
        else {
            $item = end($leaps);
            $this->month = $item['month'];
            $this->jd = $item['new_moon']->getJd();
        }
}
    /**
     * {@inheritdoc}
     */
    public function isLeap(): bool 
    { 
        return in_array(
            $this->newMoon11th->getYear() % 19,
            [0, 3, 6, 9, 11, 14, 17]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getMonth(): int 
    { 
        return $this->month;
    }
}