<?php namespace VanTran\LunarCalendar\Correctors;

use VanTran\LunarCalendar\Converters\GregorianToJDNConverter;
use VanTran\LunarCalendar\Converters\JdnToNewMoonPhaseConverter;
use VanTran\LunarCalendar\Converters\WinterSolsticeNewMoonConverter;

/**
 * Bộ chuyển đổi - khớp ngày tháng Âm lịch từ một mốc Dương lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Correctors
 */
class GregorianToLunarCorrector extends LunarDateTimeCorrector
{
    /**
     * @var bool Xác định Âm lịch và Dương lịch có cùng số năm hay không
     */
    private bool $sameYear = true;

    protected function init(): void
    {
        // Khởi tạo số ngày Julian
        $this->initJd();

        // Khởi tạo điểm sóc từ dương lịch đầu vào
        $this->initNewMoon();

        // Khởi tạo điểm Sóc tháng 11 Âm lịch
        $this->initWsNewMoon();

        // Khởi tạo tháng nhuận
        $this->initLeapMonth();

        // Khởi tạo số ngày của tháng
        $this->initDayOfMonth();

        // Biến đổi đầu vào thành thời gian Âm lịch
        $this->mutateInput();
    }

    /**
     * Biến đổi lớp đầu vào từ lưu trữ ngày tháng Dương lịch thành ngày tháng Âm lịch
     * 
     * @return void 
     */
    protected function mutateInput(): void
    {
        $leap = $this->getLeapMonth();
        $nm = $this->getNewMoon();
        $wsnm = $this->getWsNewMoon();

        $year = ($this->sameYear) ? $this->storage->getYear() : $this->storage->getYear() - 1;
        $day = $this->getMidnightJd() - $nm->getMidnightJd() + 1;
        $month = (function () use ($leap, $nm, $wsnm): int 
        {
            $phases = $wsnm->getTotalCycles() - $nm->getTotalCycles();

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
                    if (
                        $leap->getMidnightJd() !== $nm->getMidnightJd() &&
                        $val < $leap->getMonth() &&
                        $leap->getMonth() != 11
                    ) {
                        $val --;
                    }
                }
            }

            return $val;
        })();

        // Biến đổi kho lưu trữ
        $this->storage->setDay($day);
        $this->storage->setMonth($month);
        $this->storage->setYear($year);

        if ($leap->isLeap() && $leap->getMidnightJd() == $nm->getMidnightJd()) {
             $this->storage->setIsLeapMonth(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initNewMoon(): void
    {
        $this->newMoon = new JdnToNewMoonPhaseConverter($this);
    }

    /**
     * khởi tạo mốc ngày Julian từ dương lịch đầu vào
     * @return void 
     */
    protected function initJd(): void
    {
        $input = $this->getDateTimeStorage();

        $jd = new GregorianToJDNConverter(
            $input->getYear(),
            $input->getMonth(),
            $input->getDay(),
            $input->getHour(),
            $input->getMinute(),
            $input->getSecond(),
            $input->getOffset(),
        );

        $this->jd = $jd->getJd();
    }

    /**
     * {@inheritdoc}
     */
    protected function initWsNewMoon(): void
    {
        parent::initWsNewMoon();

        /**
         * Trường hợp năm mới Dương lịch đến trước năm mới Âm lịch (thường rơi vào các tháng 1 và 2 dương lịch, tương
         * ứng với khoảng tháng 11 - 12 Âm lịch năm cũ), thì điểm Sóc tháng 11 cần tính là điểm của năm trước đó nữa.
         * Đoạn mã sau đây xác định điểm Sóc hiện tại thuộc về năm Âm lịch tương ứng vói năm Dương lịch, hoặc thuộc về
         * năm trước đó.
         */
        $totalPhase11th = $this->getWsNewMoon()->getTotalCycles();
        $totalPhaseCurrent = $this->getNewMoon()->getTotalCycles();

        if (11 - ($totalPhase11th - $totalPhaseCurrent) <= 0) {
            $this->sameYear = false;

            $this->wsNewMoon = new WinterSolsticeNewMoonConverter(
                $this->storage->getYear() - 1,
                $this->getOffset()
            );
        }
    }

    /**
     * Bởi đầu vào là dương lịch, do vậy cần ghi đè lại hàm này để loại bỏ đi các phần mã không cần thiết có thể gây lỗi
     * 
     * @return void 
     */
    protected function initDayOfMonth(): void
    {
        $nextNm = $this->getNewMoon()->add(1);
        $this->dayOfMonth = $nextNm->getMidnightJd() - $this->getNewMoon()->getMidnightJd();
    }
}