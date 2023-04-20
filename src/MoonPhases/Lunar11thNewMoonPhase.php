<?php namespace VanTran\LunarCalendar\MoonPhases;

use VanTran\LunarCalendar\Sunlongitude\BaseSunlongitude;
use VanTran\LunarCalendar\Sunlongitude\MjdToSunlongitude;

/**
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\MoonPhases
 */
class Lunar11thNewMoonPhase extends GregorianToNewMoonPhase implements Lunar11thNewMoonPhaseInterface
{
    public function __construct(protected int $year, int $offset = self::VN_OFFSET)
    {
        /**
         * Để tìm điểm Sóc của tháng 11 Âm lịch nhiều cách, nhưng phương pháp trình bày dưới đây ngắn gọn và đơn giản:
         * 
         *  - Cho N là điểm Sóc cần tìm
         *  - Cho N1 là điểm Sóc gần nhất với ngày 30 tháng 12 năm Y bất kỳ theo Dương lịch (tương đương với khoảng 
         *    tháng 11 trong Âm lịch)
         *  - Cho N2 là điểm Sóc kế trước của điểm Sóc N1
         *  - Cho S1 là góc Kinh độ Mặt trời tại điểm N1 
         * 
         * Theo quy ước, tháng 11 của một năm Âm lịch bất kỳ phải có chứa điểm bắt đầu Trung khí Đông Chí (khi Kinh độ 
         * Mặt trời ở góc 270 độ), sẽ có 2 trường hợp xảy ra như sau:
         * 
         * 1. Khi S1 nhỏ hơn 270, tức điểm Sóc N1 thuộc về tiết trước Đông Chí, từ điểm N1 (đầu tháng) cho đến ngày
         * cuối tháng (29 - 30 ngày) chắc chắn sẽ gặp được Đông Chí, do vậy N = N1.
         * 2. Khi S1 có giá trị lớn hơn 270, tức điểm bắt đầu Đông chí đã qua rồi, do đó phải tính điểm Sóc của chu kỳ 
         * Mặt trăng trước đó tức N2, khi đó N = N2.
         * 
         * Cần lưu ý, khi lập lịch, điểm Sóc và góc KDMT được lấy lúc 00:00 (nửa đêm) theo giờ địa phương.
         */

        parent::__construct(
            $offset,
            $this->year,
            12,
            30
        );

        $sl = new BaseSunlongitude($this->getMidnightJd(), 0);

        if ($sl->getDegrees() >= 270) {
            $nm = $this->subtract(1);
            
            $this->setJd($nm->getJd());
            $this->setTotalCycles($nm->getTotalCycles());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getYear(): int 
    {
        return $this->year;
    }
}