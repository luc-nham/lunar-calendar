<?php namespace VanTran\LunarCalendar\Converters;

use Exception;
use VanTran\LunarCalendar\Interfaces\LunarLeapMonthInterface;
use VanTran\LunarCalendar\Interfaces\MoonPhaseInterface;
use VanTran\LunarCalendar\Interfaces\WinterSolsticeNewMoonInterface;

/**
 * Lớp tính toán và chuyển đổi điểm Sóc tháng 11 của 1 năm Âm lịch thành điểm bắt đầu của tháng nhuận (nếu có)
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class LunarLeapMonthConverter extends BaseJDN implements LunarLeapMonthInterface
{
     /**
     * @var null|int Số (vị trí) tháng nhuận âm lịch, null nếu không có tháng nhuận
     */
    protected $month;

    /**
     * Tạo đối tượng mới
     * 
     * @param WinterSolsticeNewMoonInterface $wsNewMoon Điểm Sóc tháng 11 của 1 năm Âm lịch
     * @return void 
     * @throws Exception 
     */
    public function __construct(protected WinterSolsticeNewMoonInterface $wsNewMoon)
    {
        // Nếu năm đó không nhuận thì không cần tính nữa
        if (in_array($this->wsNewMoon->getYear() % 19, [0, 3, 6, 9, 11, 14, 17])) {
            $this->init();
        }
    }

    /**
     * Khởi tạo dữ liệu
     * 
     * @return void 
     * @throws Exception 
     */
    protected function init(): void
    {
        $lastNewMoon = new WinterSolsticeNewMoonConverter(
            $this->wsNewMoon->getYear() - 1, 
            $this->getOffset()
        );

        $counter = 0;
        $result = [];
        $nm = $lastNewMoon->add(1);
        $getDeg = function (MoonPhaseInterface $newMoonPhase): int
        {
            $sl = new JdnToSunlongitudeConverter($newMoonPhase);
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
            $this->jd = $this->wsNewMoon->add(1)->getJd();
        }
        else {
            $this->month = $result[0]['month'];
            $this->setJd($result[0]['jd']);
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
        return $this->wsNewMoon->getOffset();
    }

    /**
     * Trả về số ngày Julian điểm Sóc của tháng nhuận nếu có, nếu không ném ra ngoại lệ.
     * 
     * @return float 
     * @throws Exception 
     */
    public function getJd(): float
    {
        if (!$this->isLeap()) {
            throw new Exception("The current lunar year has't leap month.");
        }

        return parent::getJd();
    }
}