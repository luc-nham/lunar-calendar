<?php namespace VanTran\LunarCalendar\Lunar;

use Exception;
use VanTran\LunarCalendar\Mjd\BaseMjd;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhase;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\NewMoonPhaseInterface;
use VanTran\LunarCalendar\Sunlongitude\MjdToSunlongitude;

/**
 * Lớp tính toán tháng nhuận âm lịch từ điểm Sóc tháng 11 của năm Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
class LunarLeapMonth extends BaseMjd implements LunarLeapMonthInterface
{
    /**
     * @var null|int Số (vị trí) tháng nhuận âm lịch, null nếu không có tháng nhuận
     */
    protected $month;

    /**
     * Tạo đối tượng mới
     * 
     * @param Lunar11thNewMoonPhaseInterface $newMoon11th Điểm Sóc tháng 11 của 1 năm Âm lịch
     * @return void 
     * @throws Exception 
     */
    public function __construct(protected Lunar11thNewMoonPhaseInterface $newMoon11th)
    {
        // Nếu năm đó không nhuận thì không cần tính nữa
        if (in_array($this->newMoon11th->getYear() % 19, [0, 3, 6, 9, 11, 14, 17])) {
            $this->init();
        }
    }

    /**
     * Khởi tạo dữ liệu
     * @return void 
     * @throws Exception 
     */
    protected function init(): void
    {
        $lastNewMoon = new Lunar11thNewMoonPhase(
            $this->newMoon11th->getYear() - 1, 
            $this->getOffset()
        );

        $counter = 0;
        $result = [];
        $nm = $lastNewMoon->add(1);
        $getDeg = function (NewMoonPhaseInterface $newMoonPhase): int
        {
            $sl = new MjdToSunlongitude($newMoonPhase);
            return floor($sl->getMidnightDegrees() / 30);
        };

        do {
            $nextNm = $nm->add(1);
            $deg = $getDeg($nm);
            $nextDeg = $getDeg($nextNm);

            if ($deg == $nextDeg) {
                array_push($result, [
                    'month' => $counter - 1,
                    'jd'    => $nm->getJd()
                ]);
            }

            $nm = $nextNm;

            $counter ++;
        } while ($counter < 13);

        $counter = count($result);
        
        if ($counter === 0) {
            throw new Exception("Error. Can not find the leap month.");
        }

        if ($counter > 1) {
            $this->month = 11;
            $this->jd = $this->newMoon11th->add(1)->getJd();
        }
        else {
            $this->month = $result[0]['month'];
            $this->jd = $result[0]['jd'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isLeap(): bool 
    { 
        return ($this->getMonth()) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getMonth(): null|int 
    { 
        return $this->month;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int
    {
        return $this->newMoon11th->getOffset();
    }
}