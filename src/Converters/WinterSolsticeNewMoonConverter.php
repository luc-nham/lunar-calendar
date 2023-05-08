<?php namespace VanTran\LunarCalendar\Converters;

use Exception;
use VanTran\LunarCalendar\Interfaces\WinterSolsticeNewMoonInterface;

/**
 * Bộ chuyển đổi năm Âm lịch thành điểm Sóc tháng 11 Âm lịch tương ứng - Tức điểm Trăng mới có chứa Đông Chí.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class WinterSolsticeNewMoonConverter extends JdnToNewMoonPhaseConverter implements WinterSolsticeNewMoonInterface
{
    /**
     * Tạo đối tượng mới
     * 
     * @param int $year Năm Âm lịch cần tìm
     * @param int $offset Chênh lệch giờ địa phương
     * @return void 
     * @throws Exception 
     */
    public function __construct(protected int $year, int $offset = 0)
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

        parent::__construct(new GregorianToJDNConverter(
            $this->getYear(),
            12,
            31,
            0,
            0,
            0,
            $offset
        ));

        $sunC = new JdnToSunlongitudeConverter($this);

        if ($sunC->getMidnightDegrees() >= 270) {
            $prevPhase = $this->subtract(1);
            
            $this->setJd($prevPhase->getJd());
            $this->setTotalCycles($prevPhase->getTotalCycles());
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